<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Service\BookTransformer;
use App\Sustainability\Entity\Indicator;
use App\Sustainability\Entity\Measurement;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 * API-kontroller för kortspel, bibliotek och hållbarhetsdata.
 */
class ApiController extends AbstractController
{
    #[Route('/proj/api', name: 'proj_api_overview', methods: ['GET'])]
    public function projApiOverview(): Response
    {
        return $this->render('proj/api.html.twig');
    }

    #[Route('/api', name: 'api')]
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'routes' => [
                // Kortleks-API
                ['name' => 'GET /api/deck', 'path' => $this->generateUrl('api_deck'), 'method' => 'GET', 'description' => 'Returnerar en sorterad kortlek.'],
                ['name' => 'GET /api/deck/shuffle', 'path' => $this->generateUrl('api_deck_shuffle'), 'method' => 'GET', 'description' => 'Blandar kortleken.'],
                ['name' => 'GET /api/deck/draw', 'path' => $this->generateUrl('api_draw_one'), 'method' => 'GET', 'description' => 'Drar ett kort.'],
                ['name' => 'GET /api/deck/draw/{number}', 'path' => $this->generateUrl('api_draw_number', ['number' => 3]), 'method' => 'GET', 'description' => 'Drar flera kort.'],
                ['name' => 'GET /api/deck/deal/{players}/{cards}', 'path' => $this->generateUrl('api_deal', ['players' => 2, 'cards' => 5]), 'method' => 'GET', 'description' => 'Delar ut kort.'],

                // Övriga
                ['name' => 'GET /api/quote', 'path' => $this->generateUrl('api_quote'), 'method' => 'GET', 'description' => 'Returnerar ett slumpmässigt citat.'],
                ['name' => 'GET /api/game', 'path' => $this->generateUrl('api_game'), 'method' => 'GET', 'description' => 'Returnerar ställning i spelet 21.'],

                // Biblioteks-API
                ['name' => 'GET /api/library/books', 'path' => $this->generateUrl('api_library_books'), 'method' => 'GET', 'description' => 'Returnerar alla böcker.'],

                // Hållbarhetsprojekt
                ['name' => 'GET /api/proj/indicators', 'path' => $this->generateUrl('api_proj_indicators'), 'method' => 'GET', 'description' => 'Returnerar alla indikatorer.'],
                ['name' => 'GET /api/proj/indicator/{id}', 'path' => $this->generateUrl('api_proj_indicator', ['id' => 1]), 'method' => 'GET', 'description' => 'En specifik indikator.'],
                ['name' => 'GET /api/proj/indicator/{id}/measurements', 'path' => $this->generateUrl('api_proj_measurements', ['id' => 1]), 'method' => 'GET', 'description' => 'Alla mätvärden för indikator.'],
                ['name' => 'POST /api/proj/measurement/add', 'path' => $this->generateUrl('api_proj_measurement_add'), 'method' => 'POST', 'description' => 'Lägger till ett mätvärde.'],
                ['name' => 'POST /api/proj/reset', 'path' => $this->generateUrl('api_proj_reset'), 'method' => 'POST', 'description' => 'Återställer alla mätvärden.'],
            ],
        ]);
    }

    #[Route('/api/library/books', name: 'api_library_books', methods: ['GET'])]
    public function getAllBooks(BookRepository $bookRepository, BookTransformer $transformer): JsonResponse
    {
        $books = $bookRepository->findAll();
        return $this->json($transformer->transformMany($books));
    }

    #[Route('/api/library/book/{isbn}', name: 'api_library_book', methods: ['GET'])]
    public function getBookByIsbn(string $isbn, BookRepository $bookRepository, BookTransformer $transformer): JsonResponse
    {
        $book = $bookRepository->findOneBy(['isbn' => $isbn]);
        if (!$book) {
            return $this->json(['error' => 'Book not found'], 404);
        }

        return $this->json($transformer->transform($book));
    }

    #[Route('/api/proj/indicators', name: 'api_proj_indicators', methods: ['GET'])]
    public function getIndicators(ManagerRegistry $doctrine): JsonResponse
    {
        $em = $doctrine->getManager('sustainability');
        $indicators = $em->getRepository(Indicator::class)->findAll();

        $data = array_map(function (Indicator $indicator) {
            return [
                'id' => $indicator->getId(),
                'name' => $indicator->getName(),
                'description' => $indicator->getDescription(),
            ];
        }, $indicators);

        return $this->json($data);
    }

    #[Route('/api/proj/indicator/{id}', name: 'api_proj_indicator', methods: ['GET'])]
    public function getIndicatorById(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $em = $doctrine->getManager('sustainability');
        $indicator = $em->getRepository(Indicator::class)->find($id);

        if (!$indicator) {
            return $this->json(['error' => 'Indicator not found'], 404);
        }

        return $this->json([
            'id' => $indicator->getId(),
            'name' => $indicator->getName(),
            'description' => $indicator->getDescription(),
        ]);
    }

    #[Route('/api/proj/indicator/{id}/measurements', name: 'api_proj_measurements', methods: ['GET'])]
    public function getMeasurementsByIndicator(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $em = $doctrine->getManager('sustainability');
        $indicator = $em->getRepository(Indicator::class)->find($id);

        if (!$indicator) {
            return $this->json(['error' => 'Indicator not found'], 404);
        }

        $data = [];
        foreach ($indicator->getMeasurements() as $measurement) {
            $data[] = [
                'id' => $measurement->getId(),
                'year' => $measurement->getYear(),
                'value' => $measurement->getValue(),
                'country' => $measurement->getCountry(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/api/proj/measurement/add', name: 'api_proj_measurement_add', methods: ['POST'])]
    public function addMeasurement(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['indicator_id'], $data['year'], $data['value'], $data['country'])) {
            return $this->json(['error' => 'Ogiltig inmatning'], 400);
        }

        $em = $doctrine->getManager('sustainability');
        $indicator = $em->getRepository(Indicator::class)->find($data['indicator_id']);

        if (!$indicator) {
            return $this->json(['error' => 'Indikator hittades inte'], 404);
        }

        $measurement = new Measurement();
        $measurement->setIndicator($indicator);
        $measurement->setYear((int) $data['year']);
        $measurement->setValue((float) $data['value']);
        $measurement->setCountry($data['country']);
        $measurement->setUnit($data['unit'] ?? '');
        $measurement->setSource('added');


        $em->persist($measurement);
        $em->flush();

        return $this->json(['message' => 'Mätvärde tillagt!']);

    }

    #[Route('/api/proj/reset', name: 'api_proj_reset', methods: ['POST'])]
    public function resetData(ManagerRegistry $doctrine): JsonResponse
    {
        try {
            $em = $doctrine->getManager('sustainability');
            assert($em instanceof EntityManagerInterface);

            $conn = $em->getConnection();

            $conn->executeStatement("DELETE FROM measurement WHERE source = 'added'");
            $conn->executeStatement("DELETE FROM measurement WHERE source = 'original'");
            $conn->executeStatement("
                INSERT INTO measurement (indicator_id, year, value, unit, country, source)
                SELECT indicator_id, year, value, unit, country, 'original'
                FROM measurement_original
            ");

            return $this->json(['message' => 'Datan har återställts till original.']);
        } catch (\Throwable $e) {
            return $this->json([
                'error' => 'Fel vid återställning',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

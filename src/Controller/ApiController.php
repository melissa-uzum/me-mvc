<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Service\BookTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
/**
 * API-kontroller som hanterar JSON-endpoints för kortlek, spel och böcker.
 */
class ApiController extends AbstractController
{
    #[Route('/api', name: 'api')]
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'routes' => [
                [
                    'name' => 'GET /api/deck',
                    'path' => $this->generateUrl('api_deck'),
                    'method' => 'GET',
                    'description' => 'Returnerar en sorterad kortlek som JSON.',
                ],
                [
                    'name' => 'GET /api/deck/shuffle',
                    'path' => $this->generateUrl('api_deck_shuffle'),
                    'method' => 'GET',
                    'description' => 'Blandar kortleken och returnerar den som JSON.',
                ],
                [
                    'name' => 'GET /api/deck/draw',
                    'path' => $this->generateUrl('api_draw_one'),
                    'method' => 'GET',
                    'description' => 'Drar ett kort från kortleken.',
                ],
                [
                    'name' => 'GET /api/deck/draw/{number}',
                    'path' => $this->generateUrl('api_draw_number', ['number' => 3]),
                    'method' => 'GET',
                    'description' => 'Drar valfritt antal kort från kortleken (exempel 3 kort).',
                ],
                [
                    'name' => 'GET /api/deck/deal/{players}/{cards}',
                    'path' => $this->generateUrl('api_deal', ['players' => 2, 'cards' => 5]),
                    'method' => 'GET',
                    'description' => 'Delar ut kort till flera spelare (exempel 2 spelare, 5 kort vardera).',
                ],
                [
                    'name' => 'GET /api/quote',
                    'path' => $this->generateUrl('api_quote'),
                    'method' => 'GET',
                    'description' => 'Returnerar ett slumpmässigt citat.',
                ],
                [
                    'name' => 'GET /api/game',
                    'path' => $this->generateUrl('api_game'),
                    'method' => 'GET',
                    'description' => 'Returnerar ställning för spelet 21.',
                ],
                [
                    'name' => 'GET /api/library/books',
                    'path' => $this->generateUrl('api_library_books'),
                    'method' => 'GET',
                    'description' => 'Returnerar alla böcker i biblioteket som JSON.',
                ],
                [
                    'name' => 'GET /api/library/book/{isbn}',
                    'path' => $this->generateUrl('api_library_book', ['isbn' => '9781804940822']),
                    'method' => 'GET',
                    'description' => 'Returnerar en specifik bok baserat på ISBN (exempel Wool).',
                ],
                [
                    'name' => 'GET /api/proj/indicators',
                    'path' => $this->generateUrl('api_proj_indicators'),
                    'method' => 'GET',
                    'description' => 'Returnerar alla indikatorer.',
                ],
                [
                    'name' => 'GET /api/proj/indicator/{id}',
                    'path' => $this->generateUrl('api_proj_indicator', ['id' => 1]),
                    'method' => 'GET',
                    'description' => 'Returnerar en indikator med angivet id.',
                ],
                [
                    'name' => 'GET /api/proj/indicator/{id}/measurements',
                    'path' => $this->generateUrl('api_proj_measurements', ['id' => 1]),
                    'method' => 'GET',
                    'description' => 'Returnerar alla mätvärden för en viss indikator.',
                ],
                [
                    'name' => 'POST /api/proj/indicator/add',
                    'path' => $this->generateUrl('api_proj_indicator_add'),
                    'method' => 'POST',
                    'description' => 'Lägger till en ny indikator (kräver JSON body).',
                ],
                [
                    'name' => 'POST /api/proj/measurement/add',
                    'path' => $this->generateUrl('api_proj_measurement_add'),
                    'method' => 'POST',
                    'description' => 'Lägger till ett nytt mätvärde (kräver JSON body).',
                ],
            ],
        ]);
    }

    /**
     * Returnerar alla böcker i biblioteket som JSON.
     */
    #[Route('/api/library/books', name: 'api_library_books', methods: ['GET'])]
    public function getAllBooks(BookRepository $bookRepository, BookTransformer $transformer): JsonResponse
    {
        $books = $bookRepository->findAll();
        return $this->json($transformer->transformMany($books));
    }

    /**
     * Returnerar en bok baserat på ISBN, eller 404 om den inte hittas.
     */
    #[Route('/api/library/book/{isbn}', name: 'api_library_book', methods: ['GET'])]
    public function getBookByIsbn(string $isbn, BookRepository $bookRepository, BookTransformer $transformer): JsonResponse
    {
        $book = $bookRepository->findOneBy(['isbn' => $isbn]);

        if (!$book) {
            return $this->json(['error' => 'Book not found'], 404);
        }

        return $this->json($transformer->transform($book));
    }

    #[Route('/api/proj', name: 'api_proj_index', methods: ['GET'])]
public function projApiIndex(): JsonResponse
{
    return $this->json([
        'message' => 'API för hållbarhetsprojektet',
        'routes' => [
            '/api/proj/indicators',
            '/api/proj/indicator/1',
            '/api/proj/indicator/1/measurements',
            '/api/proj/indicator/add',
            '/api/proj/measurement/add',
        ]
    ]);
}

#[Route('/api/proj/indicators', name: 'api_proj_indicators', methods: ['GET'])]
public function getIndicators(ManagerRegistry $doctrine): JsonResponse
{
    $connection = $doctrine->getConnection('sustainability');
    $data = $connection->fetchAllAssociative('SELECT * FROM indicator');
    return $this->json($data);
}

#[Route('/api/proj/indicator/{id}', name: 'api_proj_indicator', methods: ['GET'])]
public function getIndicatorById(int $id, ManagerRegistry $doctrine): JsonResponse
{
    $connection = $doctrine->getConnection('sustainability');
    $data = $connection->fetchAssociative('SELECT * FROM indicator WHERE id = ?', [$id]);

    if (!$data) {
        return $this->json(['error' => 'Indicator not found'], 404);
    }

    return $this->json($data);
}

    #[Route('/api/proj/indicator/{id}/measurements', name: 'api_proj_measurements', methods: ['GET'])]
    public function getMeasurementsByIndicator(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $connection = $doctrine->getConnection('sustainability');
        $data = $connection->fetchAllAssociative('SELECT * FROM measurement WHERE indicator_id = ? ORDER BY year', [$id]);
        return $this->json($data);
    }

    #[Route('/api/proj/indicator/add', name: 'api_proj_indicator_add', methods: ['POST'])]
    public function addIndicator(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name'], $data['description'], $data['goal'])) {
            return $this->json(['error' => 'Invalid input'], 400);
        }

        $connection = $doctrine->getConnection('sustainability');
        $connection->insert('indicator', [
            'name' => $data['name'],
            'description' => $data['description'],
            'goal' => (int)$data['goal']
        ]);

        return $this->json(['message' => 'Indicator added']);
    }

    #[Route('/api/proj/measurement/add', name: 'api_proj_measurement_add', methods: ['POST'])]
    public function addMeasurement(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['indicator_id'], $data['year'], $data['value'], $data['unit'])) {
            return $this->json(['error' => 'Ogiltig inmatning'], 400);
        }

        $connection = $doctrine->getConnection('sustainability');

        $connection->insert('measurement', [
            'indicator_id' => (int)$data['indicator_id'],
            'year' => (int)$data['year'],
            'value' => (float)$data['value'],
            'unit' => $data['unit'],
            'country' => $data['country'] ?? null,
            'source' => 'added'
        ]);


        return $this->json(['message' => 'Mätvärde tillagt!']);
    }

    #[Route('/api/proj/reset', name: 'api_proj_reset', methods: ['POST'])]
public function resetData(ManagerRegistry $doctrine): JsonResponse
{
    try {
        $connection = $doctrine->getConnection('sustainability');

        $connection->executeStatement("DELETE FROM measurement WHERE source = 'added'");

        return $this->json(['message' => 'Alla tillagda mätvärden har raderats.']);
    } catch (\Throwable $e) {
        return $this->json([
            'error' => 'Fel vid återställning: ' . $e->getMessage()
        ], 500);
    }
}
#[Route('/proj/api', name: 'proj_api')]
    public function show(): Response
    {
        return $this->render('proj/api.html.twig', [
            'routes' => [
                ['method' => 'GET', 'path' => '/api/proj/indicators'],
                ['method' => 'GET', 'path' => '/api/proj/indicator/1'],
                ['method' => 'GET', 'path' => '/api/proj/indicator/1/measurements'],
                ['method' => 'POST', 'path' => '/api/proj/indicator/add'],
                ['method' => 'POST', 'path' => '/api/proj/measurement/add'],
                ['method' => 'POST', 'path' => '/api/proj/reset']
            ]
        ]);
    }

}

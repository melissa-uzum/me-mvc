<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
            ],
        ]);
    }

    #[Route('/api/library/books', name: 'api_library_books', methods: ['GET'])]
    public function getAllBooks(BookRepository $bookRepository): JsonResponse
    {
        $books = $bookRepository->findAll();

        $data = array_map(function ($book) {
            return [
                'title' => $book->getTitle(),
                'isbn' => $book->getIsbn(),
                'author' => $book->getAuthor(),
                'image' => $book->getImage(),
                'language' => $book->getLanguage(),
                'pages' => $book->getPages(),
                'published_at' => $book->getPublishedAt()?->format('Y-m-d'),
                'publisher' => $book->getPublisher(),
            ];
        }, $books);

        return $this->json($data);
    }

    #[Route('/api/library/book/{isbn}', name: 'api_library_book', methods: ['GET'])]
    public function getBookByIsbn(string $isbn, BookRepository $bookRepository): JsonResponse
    {
        $book = $bookRepository->findOneBy(['isbn' => $isbn]);

        if (!$book) {
            return $this->json(['error' => 'Book not found'], 404);
        }

        return $this->json([
            'title' => $book->getTitle(),
            'isbn' => $book->getIsbn(),
            'author' => $book->getAuthor(),
            'image' => $book->getImage(),
            'language' => $book->getLanguage(),
            'pages' => $book->getPages(),
            'published_at' => $book->getPublishedAt()?->format('Y-m-d'),
            'publisher' => $book->getPublisher(),
        ]);
    }
}

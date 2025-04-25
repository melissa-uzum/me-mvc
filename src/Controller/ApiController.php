<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api', name: 'api')]
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'routes' => [
                [
                    'name' => 'GET /api/deck',
                    'path' => '/api/deck',
                    'method' => 'GET',
                    'description' => 'Returnerar en sorterad kortlek som JSON.',
                ],
                [
                    'name' => 'GET /api/deck/shuffle',
                    'path' => '/api/deck/shuffle',
                    'method' => 'GET',
                    'description' => 'Blandar kortleken och returnerar den som JSON.',
                ],
                [
                    'name' => 'GET /api/deck/draw',
                    'path' => '/api/deck/draw',
                    'method' => 'GET',
                    'description' => 'Drar ett kort från kortleken.',
                ],
                [
                    'name' => 'GET /api/deck/draw/{number}',
                    'path' => '/api/deck/draw/3',
                    'method' => 'GET',
                    'description' => 'Drar valfritt antal kort från kortleken.',
                ],
                [
                    'name' => 'GET /api/deck/deal/{players}/{cards}',
                    'path' => '/api/deck/deal/2/5',
                    'method' => 'GET',
                    'description' => 'Delar ut kort till flera spelare.',
                ],
                [
                    'name' => 'GET /api/quote',
                    'path' => '/api/quote',
                    'method' => 'GET',
                    'description' => 'Returnerar ett slumpmässigt citat.',
                ],
                [
                    'name' => 'GET /api/game',
                    'path' => '/api/game',
                    'method' => 'GET',
                    'description' => 'Returnerar ställning för spelet 21.',
                ],
            ],
        ]);
    }
}

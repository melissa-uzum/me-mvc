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
                    'description' => 'Returnerar en sorterad kortlek som JSON.'
                ],
                [
                    'name' => 'POST /api/deck/shuffle',
                    'path' => '/api/deck/shuffle',
                    'description' => 'Blandar kortleken och returnerar den som JSON. Kortleken sparas i sessionen.'
                ],
                [
                    'name' => 'POST /api/deck/draw',
                    'path' => '/api/deck/draw',
                    'description' => 'Drar ett kort från kortleken. Returnerar kortet och antalet kvar i JSON-format.'
                ],
                [
                    'name' => 'POST /api/deck/draw/{number}',
                    'path' => '/api/deck/draw/3',
                    'description' => 'Drar valfritt antal kort från kortleken och visar dem i JSON-format.'
                ],
                [
                    'name' => 'POST /api/deck/deal/{players}/{cards}',
                    'path' => '/api/deck/deal/2/5',
                    'description' => 'Delar ut kort till flera spelare. Returnerar varje spelares hand i JSON.'
                ],
                [
                    'name' => 'GET /api/quote',
                    'path' => '/api/quote',
                    'description' => 'Returnerar ett slumpmässigt citat i JSON-format.'
                ],
            ]
        ]);
    }
}

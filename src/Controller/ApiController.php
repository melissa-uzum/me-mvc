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
            ],
        ]);
    }
}

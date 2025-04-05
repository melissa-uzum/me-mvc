<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class QuoteApiController
{
    #[Route('/api/quote', name: 'api_quote')]
    public function quote(): JsonResponse
    {
        $quotes = [
            "Code is like poetry, sometimes you understand it other times youre left confused..",
            "Keep calm and commit often.",
            "Symfony makes life easier - sort of.",
            "Anything is possible with PHP and a cup of water (I dont drink coffee...).",

        ];

        return new JsonResponse([
            'quote' => $quotes[array_rand($quotes)],
            'date' => date('Y-m-d'),
            'timestamp' => date('H:i:s'),
        ]);
    }
}


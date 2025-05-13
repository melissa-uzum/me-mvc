<?php

namespace App\Service\Deck;

use App\Service\DeckApiService;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Responderklass för att hämta och sortera kortleken.
 */
class GetDeckResponder
{
    public function __construct(
        private DeckApiService $deckService
    ) {}

    /**
     * Returnerar den aktuella kortleken i sorterad ordning.
     *
     * @return JsonResponse JSON-svar med hela kortleken och antal kort.
     */
    public function __invoke(): JsonResponse
    {
        $deck = $this->deckService->getDeck();
        $cards = $deck->getCards();

        usort($cards, fn($a, $b) => $a->getSortOrder() <=> $b->getSortOrder());

        return $this->createResponse($cards);
    }

    /**
     * Skapar ett JSON-svar med sorterade kort och deras antal.
     *
     * @param array $cards De sorterade korten.
     * @return JsonResponse
     */
    private function createResponse(array $cards): JsonResponse
    {
        return new JsonResponse([
            'deck' => $this->deckService->stringifyCards($cards),
            'count' => count($cards),
        ]);
    }
}

<?php

namespace App\Service\Deck;

use App\Card\DeckOfCards;
use App\Service\DeckApiService;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Responderklass för att skapa och blanda en ny kortlek.
 */
class ShuffleDeckResponder
{
    public function __construct(
        private DeckApiService $deckService
    ) {}

    /**
     * Blandar en ny kortlek och returnerar resultatet.
     *
     * @return JsonResponse JSON-svar med blandad kortlek och antal kort.
     */
    public function __invoke(): JsonResponse
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $this->deckService->saveDeck($deck);

        return $this->createResponse($deck);
    }

    /**
     * Skapar ett JSON-svar med blandad kortlek och antal kort.
     *
     * @param DeckOfCards $deck Den blandade kortleken.
     * @return JsonResponse
     */
    private function createResponse(DeckOfCards $deck): JsonResponse
    {
        return new JsonResponse([
            'shuffled_deck' => $this->deckService->stringifyCards($deck->getCards()),
            'count' => $deck->count(),
        ]);
    }
}

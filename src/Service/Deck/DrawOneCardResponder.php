<?php

namespace App\Service\Deck;

use App\Service\DeckApiService;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Responderklass för att dra ett kort från kortleken.
 */
class DrawOneCardResponder
{
    public function __construct(
        private DeckApiService $deckService
    ) {
    }

    /**
     * Anropas för att dra ett kort från kortleken.
     *
     * @return JsonResponse JSON-svar med draget kort och återstående kort i leken.
     */
    public function __invoke(): JsonResponse
    {
        $deck = $this->deckService->getDeck();
        $drawn = $deck->draw(1);
        $this->deckService->saveDeck($deck);

        return $this->createResponse($drawn, $deck->count());
    }

    /**
     * Skapar ett JSON-svar med kortet som drogs och antal kort kvar i leken.
     *
     * @param array $drawn Det dragna kortet.
     * @param int $remaining Antal återstående kort i leken.
     * @return JsonResponse
     */
    private function createResponse(array $drawn, int $remaining): JsonResponse
    {
        return new JsonResponse([
            'drawn' => $this->deckService->stringifyCards($drawn),
            'remaining' => $remaining,
        ]);
    }
}

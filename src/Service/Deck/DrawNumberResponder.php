<?php

namespace App\Service\Deck;

use App\Service\DeckApiService;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Responderklass för att dra ett angivet antal kort från kortleken.
 */
class DrawNumberResponder
{
    public function __construct(
        private DeckApiService $deckService
    ) {}

    /**
     * Drar ett visst antal kort från kortleken.
     *
     * @param int $number Antal önskade kort.
     * @return JsonResponse JSON-svar med de dragna korten och återstående kort.
     */
    public function draw(int $number): JsonResponse
    {
        $deck = $this->deckService->getDeck();
        $count = $this->calculateDrawCount($number, $deck->count());
        $drawn = $deck->draw($count);
        $this->deckService->saveDeck($deck);

        return $this->createResponse($number, $drawn, $deck->count());
    }

    /**
     * Returnerar det faktiska antalet kort som kan dras.
     *
     * @param int $requested Antal önskade kort.
     * @param int $available Antal tillgängliga kort.
     * @return int Antal som kan dras.
     */
    private function calculateDrawCount(int $requested, int $available): int
    {
        return min($requested, $available);
    }

    /**
     * Skapar ett JSON-svar med information om dragningen.
     *
     * @param int $requested Antal önskade kort.
     * @param array $drawn Dragna kort.
     * @param int $remaining Antal återstående kort i leken.
     * @return JsonResponse
     */
    private function createResponse(int $requested, array $drawn, int $remaining): JsonResponse
    {
        return new JsonResponse([
            'requested' => $requested,
            'drawn' => $this->deckService->stringifyCards($drawn),
            'remaining' => $remaining,
        ]);
    }
}

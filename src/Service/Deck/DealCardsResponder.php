<?php

namespace App\Service\Deck;

use App\Service\DeckApiService;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Responderklass för att hantera utdelning av kort till spelare.
 */
class DealCardsResponder
{
    public function __construct(
        private DeckApiService $deckService
    ) {}

    /**
     * Delar ut kort till ett antal spelare.
     *
     * @param int $players Antal spelare.
     * @param int $cards Antal kort per spelare.
     * @return JsonResponse JSON-svar med utdelade händer eller felmeddelande.
     */
    public function deal(int $players, int $cards): JsonResponse
    {
        $deck = $this->deckService->getDeck();

        if ($this->notEnoughCards($deck->count(), $players, $cards)) {
            return $this->errorResponse($deck->count());
        }

        $hands = $this->dealHands($deck, $players, $cards);
        $this->deckService->saveDeck($deck);

        return new JsonResponse([
            'hands' => $hands,
            'remaining' => $deck->count(),
        ]);
    }

    /**
     * Kontrollerar om det finns tiillräcklgt med kort kvar.
     */
    private function notEnoughCards(int $remaining, int $players, int $cards): bool
    {
        return $remaining < ($players * $cards);
    }

    /**
     * Returnerar ett felmeddelande om korten inte räcker.
     */
    private function errorResponse(int $remaining): JsonResponse
    {
        return new JsonResponse([
            'error' => 'Inte tillräckligt med kort i leken',
            'remaining' => $remaining,
        ], 400);
    }

    /**
     * Delar ut händer till spelare och returnerar dem som strängar.
     *
     * @param mixed $deck Kortlek.
     * @param int $players Antal spelare.
     * @param int $cards Antal kort per spelare.
     * @return array<string, string[]> Händers representation.
     */
    private function dealHands($deck, int $players, int $cards): array
    {
        $hands = [];

        for ($i = 1; $i <= $players; ++$i) {
            $hand = $deck->draw($cards);
            $hands["Spelare $i"] = $this->deckService->stringifyCards($hand);
        }

        return $hands;
    }
}

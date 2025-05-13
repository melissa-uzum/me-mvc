<?php

namespace App\Game;

use App\Card\Card;
use App\Card\CardHand;

/**
 * Beräknar handens totala poäng med hänsyn till valda ess-värden.
 */
class ScoreCalculator
{
    /**
     * Beräknar handens totala värde med stöd för val av ess-värde.
     *
     * @param CardHand $hand Spelarens hand.
     * @param array<int, int|null> $aceChoices Valda värden för varje ess i handen.
     * @return int Totalt poängvärde för handen.
     */
    public function calculate(CardHand $hand, array $aceChoices): int
    {
        $total = 0;

        foreach ($hand->getCards() as $index => $card) {
            $total += $this->getCardValue($card, $index, $total, $aceChoices);
        }

        return $total;
    }

    /**
     * Returnerar kortets värde, med särskild hantering för ess.
     *
     * @param Card $card Kortet som ska värderas.
     * @param int $index Kortets index i handen.
     * @param int $currentTotal Aktuellt totalt värde innan detta kort räknats.
     * @param array<int, int|null> $aceChoices Val för ess-värde om tillgängligt.
     * @return int Kortets poängvärde.
     */
    private function getCardValue(Card $card, int $index, int $currentTotal, array $aceChoices): int
    {
        if ($card->isAce()) {
            $chosen = $aceChoices[$index] ?? null;
            return $chosen ?? (($currentTotal + 14 <= 21) ? 14 : 1);
        }

        return $card->getNumericValue();
    }
}

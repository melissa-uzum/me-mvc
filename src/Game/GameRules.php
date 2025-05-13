<?php

namespace App\Game;

use App\Card\CardHand;
use App\Card\DeckOfCards;

/**
 * Innehåller reglerna för spelet 21.
 */
class GameRules
{
    /**
     * Returnerar vem som vinner rundan: 'player', 'bank' eller 'none'.
     *
     * @param CardHand $player Spelarens hand.
     * @param CardHand $bank Bankens hand.
     * @return string Vinnaren av rundan.
     */

    public function getWinner(CardHand $player, CardHand $bank, array $aceChoices = []): string
    {
        $playerValue = $this->calculateValue($player, $aceChoices);
        $bankValue = $this->calculateValue($bank);

        if ($playerValue > 21) {
            return "bank";
        }

        if ($bankValue > 21) {
            return "player";
        }

        return $bankValue >= $playerValue ? "bank" : "player";
    }

    /**
     * Returnerar om spelet är över.
     *
     * @param CardHand $player Spelarens hand.
     * @param CardHand $bank Bankens hand.
     * @param bool $playerStands Om spelaren har stannat.
     * @return bool True om spelet är över.
     */
    public function isGameOver(CardHand $player, CardHand $bank, bool $playerStands, array $aceChoices = []): bool
    {
        return $this->calculateValue($player, $aceChoices) > 21
            || ($playerStands && $this->calculateValue($bank) > 0);
    }




    /**
     * Låter banken spela sin tur genom att dra kort tills minst 17 poäng.
     *
     * @param CardHand $bank Bankens hand.
     * @param DeckOfCards $deck Kortlek att dra från.
     */
    public function playBank(CardHand $bank, DeckOfCards $deck): void
    {
        while ($this->calculateValue($bank) < 17) {
            $bank->add($deck->drawOne());
        }
    }

    /**
     * Beräknar handens totala poäng.
     *
     * @param CardHand $hand Handen att räkna poäng för.
     * @return int Handens totala värde.
     */
    public function calculateValue(CardHand $hand, array $aceChoices = []): int
    {
        $calculator = new ScoreCalculator();
        return $calculator->calculate($hand, $aceChoices);
    }

}

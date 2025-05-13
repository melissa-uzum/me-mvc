<?php

namespace App\Game;

/**
 * Beräknar modifierare för utbetalning baserat på vinnare.
 */
class PayoutCalculator
{
    /**
     * Returnerar en modifierare beroende på vem som vann rundan.
     *
     * @param string $winner Vinnaren av rundan ('player', 'bank' eller annat).
     * @return int Modifierare: 1 för spelare, -1 för banken, 0 för oavgjort.
     */
    public function getModifier(string $winner): int
    {
        return match ($winner) {
            GameBet::PLAYER => 1,
            GameBet::BANK => -1,
            default => 0,
        };
    }
}

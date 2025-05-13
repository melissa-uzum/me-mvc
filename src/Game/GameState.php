<?php

namespace App\Game;

/**
 * Hanterar tillståndet för en spelrunda i spelet 21.
 */
final class GameState
{
    private bool $playerStands = false;
    private bool $roundOver = false;

    /**
     * Markerar att spelaren har valt att stanna.
     */
    public function setPlayerStands(): void
    {
        $this->playerStands = true;
    }

    /**
     * Returnerar om spelaren har stannat.
     *
     * @return bool True om spelaren har stannat.
     */
    public function hasPlayerStood(): bool
    {
        return $this->playerStands;
    }

    /**
     * Markerar att rundan är avslutad.
     */
    public function endRound(): void
    {
        $this->roundOver = true;
    }

    /**
     * Returnerar om rundan är avslutad.
     *
     * @return bool True om rundan är över.
     */
    public function isRoundOver(): bool
    {
        return $this->roundOver;
    }

    /**
     * Återställer tillståndet för en ny runda.
     */
    public function reset(): void
    {
        $this->playerStands = false;
        $this->roundOver = false;
    }
}

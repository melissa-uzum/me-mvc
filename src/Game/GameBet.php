<?php

namespace App\Game;

use App\Game\Wallet;

/**
 * Hanterar satsningar i spelet 21.
 */
class GameBet
{
    public const PLAYER = 'player';
    public const BANK = 'bank';

    private int $bet = 0;

    /**
     * Lägger en insats om spelaren har tillräckligt med pengar.
     *
     * @param int $amount Insatsbeloppet.
     * @param Wallet $playerWallet Spelarens plånbok.
     *
     * @throws \InvalidArgumentException Om spelaren inte har råd.
     */
    public function placeBet(int $amount, Wallet $playerWallet): void
    {
        if (!$playerWallet->canAfford($amount)) {
            throw new \InvalidArgumentException("Ogiltig insats.");
        }

        $playerWallet->subtract($amount);
        $this->bet = $amount;
    }

    /**
     * Returnerar aktuell insats.
     */
    public function getBet(): int
    {
        return $this->bet;
    }

    /**
     * Återställer aktuell insats till noll.
     */
    public function reset(): void
    {
        $this->bet = 0;
    }

    /**
     * Fördelar vinsten beroende på vem som vann rundan.
     *
     * @param string $winner Vinnaren av rundan ('player' eller 'bank').
     * @param Wallet $playerWallet Spelarens plånbok.
     * @param Wallet $bankWallet Bankens plånbok.
     */
    public function applyResult(string $winner, Wallet $playerWallet, Wallet $bankWallet): void
    {
        if ($winner === 'player') {
            $bankWallet->subtract($this->bet);
            $playerWallet->add($this->bet * 2);
        } elseif ($winner === 'bank') {
            $bankWallet->add($this->bet);
        }

        $this->reset();
    }

}

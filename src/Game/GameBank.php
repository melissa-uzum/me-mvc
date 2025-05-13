<?php

namespace App\Game;

use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;

/**
 * Representerar banken i spelet 21.
 */
class GameBank
{
    private CardHand $hand;
    private Wallet $wallet;

    /**
     * Initierar bankens hand och plånbok.
     */
    public function __construct()
    {
        $this->hand = new CardHand();
        $this->wallet = new Wallet();
    }

    /**
     * Återställer objektet vid unserialisering.
     */
    public function __wakeup(): void
    {
        $this->hand ??= new CardHand();
        $this->wallet ??= new Wallet();
    }

    /**
     * Returnerar bankens hand.
     */
    public function getHand(): CardHand
    {
        return $this->hand;
    }

    /**
     * Låter banken dra kort tills summan är minst 17.
     */
    public function play(DeckOfCards $deck): void
    {
        while ($this->getTotalValue() < 17) {
            $this->hand->add($deck->drawOne());
        }
    }

    /**
     * Beräknar den totala poängen för bankens hand.
     */
    public function getTotalValue(): int
    {
        $total = 0;

        foreach ($this->hand->getCards() as $card) {
            $total += $card->isAce()
                ? (($total + 14 <= 21) ? 14 : 1)
                : $card->getNumericValue();
        }

        return $total;
    }

    /**
     * Alias för getTotalValue().
     */
    public function getValue(): int
    {
        return $this->getTotalValue();
    }

    /**
     * Återställer bankens hand.
     */
    public function resetHand(): void
    {
        $this->hand = new CardHand();
    }

    /**
     * Returnerar bankens nuvarande saldo.
     */
    public function getMoney(): int
    {
        return $this->wallet->getAmount();
    }

    /**
     * Justerar bankens saldo.
     *
     * @param int $amount Positivt eller negativt belopp.
     */
    public function adjustMoney(int $amount): void
    {
        $this->wallet->adjust($amount);
    }

    /**
     * Returnerar Wallet-objektet.
     */
    public function getWallet(): Wallet
    {
        return $this->wallet;
    }

    /**
     * Lägger till ett kort i bankens hand.
     */
    public function addCard(Card $card): void
    {
        $this->hand->add($card);
    }
}

<?php

namespace App\Game;

use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;

/**
 * Representerar banken i spelet 21.
 * Hanterar bankens hand, saldo och spelbeteende.
 */
class GameBank
{
    /**
     * Bankens aktuella hand med kort.
     */
    private CardHand $hand;

    /**
     * Bankens plånbok för att hålla reda på pengar.
     */
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
     * Returnerar bankens aktuella hand.
     */
    public function getHand(): CardHand
    {
        return $this->hand;
    }

    /**
     * Låter banken dra kort tills totalvärdet är minst 17.
     */
    public function play(DeckOfCards $deck): void
    {
        while ($this->getTotalValue() < 17) {
            $this->hand->add($deck->drawOne());
        }
    }

    /**
     * Beräknar den totala poängen för bankens hand.
     * Tar hänsyn till essens värde (1 eller 14).
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
     * Alias för getTotalValue() – används för enhetlig terminologi.
     */
    public function getValue(): int
    {
        return $this->getTotalValue();
    }

    /**
     * Återställer bankens hand inför en ny runda.
     */
    public function resetHand(): void
    {
        $this->hand = new CardHand();
    }

    /**
     * Returnerar bankens aktuella pengar.
     */
    public function getMoney(): int
    {
        return $this->wallet->getAmount();
    }

    /**
     * Justerar bankens saldo med angivet belopp.
     *
     * @param int $amount Positivt eller negativt belopp att lägga till/ska dra.
     */
    public function adjustMoney(int $amount): void
    {
        $this->wallet->adjust($amount);
    }

    /**
     * Returnerar bankens Wallet-objekt.
     */
    public function getWallet(): Wallet
    {
        return $this->wallet;
    }

    /**
     * Lägger till ett kort i bankens hand.
     *
     * @param Card $card Kortet som ska läggas till.
     */
    public function addCard(Card $card): void
    {
        $this->hand->add($card);
    }
}

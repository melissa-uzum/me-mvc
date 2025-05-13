<?php

namespace App\Game;

use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;

/**
 * Representerar spelaren i spelet 21.
 * Hanterar spelarens hand, essval och pengar.
 */
class GamePlayer
{
    private CardHand $hand;
    private AceHandler $aceHandler;
    private Wallet $wallet;

    public function __construct()
    {
        $this->hand = new CardHand();
        $this->aceHandler = new AceHandler();
        $this->wallet = new Wallet();
    }

    public function __wakeup(): void
    {
        $this->hand ??= new CardHand();
        $this->aceHandler ??= new AceHandler();
        $this->wallet ??= new Wallet();
    }

    /**
     * Drar ett kort från kortleken och lägger till i handen.
     */
    public function draw(DeckOfCards $deck): void
    {
        $card = $deck->drawOne();
        $this->addCard($card);
    }

    /**
     * Lägger till ett kort i handen och spårar eventuella ess.
     */
    public function addCard(Card $card): void
    {
        $this->hand->add($card);
        $this->aceHandler->track($this->hand);
    }

    /**
     * Sätter spelarens valda värde för ett ess.
     *
     * @param int $index Index för esset.
     * @param int $value Antingen 1 eller 14.
     */
    public function setAceValue(int $index, int $value): void
    {
        $this->aceHandler->setAceValue($index, $value, $this->hand);
        $this->aceHandler->track($this->hand);
    }


    /**
     * Returnerar alla valda ess-värden.
     *
     * @return array<int, int|null>
     */
    public function getAceChoices(): array
    {
        return $this->aceHandler->getAll();
    }

    /**
     * Returnerar spelarens totala handvärde.
     */
    public function getValue(): int
    {
        $calculator = new ScoreCalculator();
        return $calculator->calculate($this->hand, $this->aceHandler->getAll());
    }

    /**
     * Återställer spelarens hand och essval.
     */
    public function resetHand(): void
    {
        $this->hand = new CardHand();
        $this->aceHandler = new AceHandler();
    }

    /**
     * Returnerar spelarens hand.
     */
    public function getHand(): CardHand
    {
        return $this->hand;
    }

    /**
     * Returnerar spelarens plånbok.
     */
    public function getWallet(): Wallet
    {
        return $this->wallet;
    }

    /**
     * Returnerar spelarens aktuella saldo.
     */
    public function getMoney(): int
    {
        return $this->wallet->getAmount();
    }

    /**
     * Justerar spelarens pengar.
     */
    public function adjustMoney(int $amount): void
    {
        $this->wallet->adjust($amount);
    }
}

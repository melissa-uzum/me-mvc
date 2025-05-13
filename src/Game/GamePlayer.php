<?php

namespace App\Game;

use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;

/**
 * Representerar spelaren i spelet 21.
 * Hanterar spelarens hand, val för ess och plånbok.
 */
class GamePlayer
{
    /**
     * Spelarens aktuella hand.
     */
    private CardHand $hand;

    /**
     * Hanterare för spelarens essval.
     */
    private AceHandler $aceHandler;

    /**
     * Spelarens plånbok.
     */
    private Wallet $wallet;

    /**
     * Initierar spelarens hand, esshanterare och plånbok.
     */
    public function __construct()
    {
        $this->hand = new CardHand();
        $this->aceHandler = new AceHandler();
        $this->wallet = new Wallet();
    }

    /**
     * Drar ett kort från kortleken och lägger till i spelarens hand.
     */
    public function draw(DeckOfCards $deck): void
    {
        $card = $deck->drawOne();
        $this->addCard($card);
    }

    /**
     * Lägger till ett kort i handen och spårar om det är ett ess.
     */
    public function addCard(Card $card): void
    {
        $this->hand->add($card);
        $this->aceHandler->track($this->hand);
    }

    /**
     * Sätter spelarens val för essets värde (1 eller 14).
     *
     * @param int $index Index för kortet i handen.
     * @param int $value Antingen 1 eller 14.
     */
    public function setAceValue(int $index, int $value): void
    {
        $this->aceHandler->setAceValue($index, $value, $this->hand);
        $this->aceHandler->track($this->hand);
    }

    /**
     * Returnerar alla essval som spelaren gjort.
     *
     * @return array<int, int|null>
     */
    public function getAceChoices(): array
    {
        return $this->aceHandler->getAll();
    }

    /**
     * Returnerar spelarens totala poängvärde.
     */
    public function getValue(): int
    {
        $calculator = new ScoreCalculator();
        return $calculator->calculate($this->hand, $this->aceHandler->getAll());
    }

    /**
     * Återställer spelarens hand och essval inför en ny runda.
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
     * Returnerar spelarens Wallet-objekt.
     */
    public function getWallet(): Wallet
    {
        return $this->wallet;
    }

    /**
     * Returnerar spelarens nuvarande saldo.
     */
    public function getMoney(): int
    {
        return $this->wallet->getAmount();
    }

    /**
     * Justerar spelarens pengar (kan vara negativt eller positivt).
     */
    public function adjustMoney(int $amount): void
    {
        $this->wallet->adjust($amount);
    }
}

<?php

namespace App\Game;

use App\Card\DeckOfCards;
use App\Card\CardHand;
use App\Card\Card;

class Game21
{
    private DeckOfCards $deck;
    private CardHand $player;
    private CardHand $bank;
    private bool $playerStands = false;

    public function __construct()
    {
        $this->deck = new DeckOfCards();
        $this->deck->shuffle();
        $this->player = new CardHand();
        $this->bank = new CardHand();
    }

    public function playerDraw(): void
    {
        $this->player->add($this->deck->drawOne());
    }

    public function playerStands(): void
    {
        $this->playerStands = true;
    }

    public function isPlayerStanding(): bool
    {
        return $this->playerStands;
    }

    public function bankTurn(): void
    {
        while ($this->getBankValue() < 17) {
            $this->bank->add($this->deck->drawOne());
        }
    }

    public function getPlayerHand(): CardHand
    {
        return $this->player;
    }

    public function getBankHand(): CardHand
    {
        return $this->bank;
    }

    public function getPlayerValue(): int
    {
        return $this->calculateValue($this->player);
    }

    public function getBankValue(): int
    {
        return $this->calculateValue($this->bank);
    }

    public function isGameOver(): bool
    {
        return $this->getPlayerValue() > 21 || ($this->playerStands && $this->getBankValue() > 0);
    }

    public function getWinner(): string
    {
        if ($this->getPlayerValue() > 21) {
            return "bank";
        }

        if ($this->getBankValue() > 21) {
            return "player";
        }

        if ($this->playerStands) {
            if ($this->getBankValue() >= $this->getPlayerValue()) {
                return "bank";
            } else {
                return "player";
            }
        }

        return "none";
    }

        private function calculateValue(CardHand $hand): int
    {
        $total = 0;

        foreach ($hand->getCards() as $card) {
            $value = $card->getNumericValue();
            $total += $value;
        }

        foreach ($hand->getCards() as $card) {
            if ($card->getNumericValue() === 1 && $total + 13 <= 21) {
                $total += 13;
            }
        }

        return $total;
    }
}

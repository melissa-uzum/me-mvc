<?php

namespace App\Card;

class DeckOfCards
{
    private array $cards = [];
    private bool $graphic;

    public function __construct(bool $graphic = true)
    {
        $this->graphic = $graphic;

        $suits = ['♠', '♥', '♦', '♣'];
        $values = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $this->cards[] = $graphic
                    ? new CardGraphic($suit, $value)
                    : new Card($suit, $value);
            }
        }
    }

    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    public function draw(int $count = 1): array
    {
        return array_splice($this->cards, 0, $count);
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function count(): int
    {
        return count($this->cards);
    }

    public function drawOne(): Card
    {
        if (empty($this->cards)) {
            $this->reset();
        }

        $card = array_shift($this->cards);

        if (!$card instanceof Card) {
            throw new \RuntimeException('Kunde inte dra ett kort.');
        }

        return $card;
    }

    public function reset(): void
    {
        $this->cards = [];
        $suits = ['♠', '♥', '♦', '♣'];
        $values = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $this->cards[] = $this->graphic
                    ? new CardGraphic($suit, $value)
                    : new Card($suit, $value);
            }
        }

        $this->shuffle();
    }
}

<?php

namespace App\Card;

/**
 * Representerar en kortlek med 52 spelkort.
 */
class DeckOfCards
{
    /** @var Card[] Kortleken */
    private array $cards = [];

    /** @var bool Använd grafiska kort eller ej */
    private bool $graphic;

    /**
     * Skapa en ny kortlek.
     *
     * @param bool $graphic Använd grafiska kort (true) eller vanliga kort (false)
     */
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

    /**
     * Blanda kortleken.
     */
    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    /**
     * Dra ett antal kort från början av kortleken.
     *
     * @param int $count Antal kort att dra
     * @return Card[] Dragna kort
     */
    public function draw(int $count = 1): array
    {
        return array_splice($this->cards, 0, $count);
    }

    /**
     * Hämta alla kort som är kvar.
     *
     * @return Card[] Kort som är kvar
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * Räkna hur många kort som finns kvar.
     *
     * @return int Antal kort
     */
    public function count(): int
    {
        return count($this->cards);
    }

    /**
     * Dra ett kort. Återställ kortleken om den är tom.
     *
     * @return Card Ett kort från leken
     */
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

    /**
     * Återställ och blanda kortleken.
     */
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

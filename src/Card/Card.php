<?php

namespace App\Card;

/**
 * Representerar ett spelkort med färg och värde.
 */
class Card
{
    /** Kortets färg tex. ♥ eller ♠ */
    private string $suit;

    /** Kortets värde tex. A, 10 eller K */
    private string $value;

    /**
     * Skapar ett nytt kort.
     *
     * @param string $suit  Färg
     * @param string $value Värde
     */
    public function __construct(string $suit, string $value)
    {
        $this->suit = $suit;
        $this->value = $value;
    }

    /**
     * Beräknar sorteringsvärde baserat på färg och kortets värde.
     */
    public function getSortOrder(): int
    {
        $suitOrder = [
            '♥' => 1,
            '♦' => 2,
            '♣' => 3,
            '♠' => 4,
        ];

        return $suitOrder[$this->suit] * 100 + $this->getNumericValue();
    }

    /** Returnerar kortets färg. */
    public function getSuit(): string
    {
        return $this->suit;
    }

    /** Returnerar kortets värde. */
    public function getValue(): string
    {
        return $this->value;
    }

    /** Returnerar kortet som sträng exepelvis "10♥". */
    public function __toString(): string
    {
        return "{$this->value}{$this->suit}";
    }

    /**
     * Returnerar kortets numeriska värde.
     * A=1, J=11, Q=12, K=13, annars siffervärdet.
     */
    public function getNumericValue(): int
    {
        return match ($this->value) {
            'A' => 1,
            'J' => 11,
            'Q' => 12,
            'K' => 13,
            default => (int) $this->value,
        };
    }

    /** Returnerar TRUE om kortet är ett ess. */
    public function isAce(): bool
    {
        return 'A' === $this->value;
    }
}

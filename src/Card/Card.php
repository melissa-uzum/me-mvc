<?php

namespace App\Card;

class Card
{
    private string $suit;
    private string $value;

    public function __construct(string $suit, string $value)
    {
        $this->suit = $suit;
        $this->value = $value;
    }

    public function getSuit(): string
    {
        return $this->suit;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return "{$this->value}{$this->suit}";
    }

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

    public function isAce(): bool
    {
        return 'A' === $this->value;
    }
}

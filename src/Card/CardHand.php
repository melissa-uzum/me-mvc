<?php

namespace App\Card;

use App\Card\Card;

class CardHand
{
    private array $cards = [];

    public function add(Card $card): void
    {
        $this->cards[] = $card;
    }

    public function getCards(): array
    {
        return $this->cards;
    }
}

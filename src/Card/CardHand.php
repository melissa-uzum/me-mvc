<?php

namespace App\Card;

/**
 * Representerar en hand med spelkort.
 */
class CardHand
{
    /** @var Card[] Kort i handen */
    private array $cards = [];

    /**
     * Lägg till ett kort i handen.
     */
    public function add(Card $card): void
    {
        $this->cards[] = $card;
    }

    /**
     * Hämta alla kort i handen.
     *
     * @return Card[] Kort i handen
     */
    public function getCards(): array
    {
        return $this->cards;
    }
}

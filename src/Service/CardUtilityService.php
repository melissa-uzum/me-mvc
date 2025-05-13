<?php

namespace App\Service;

use App\Card\Card;

/**
 * Tjänst för att sortera spelkort.
 */
class CardUtilityService
{
    private const SUIT_ORDER = ['♠' => 0, '♥' => 1, '♦' => 2, '♣' => 3];
    private const VALUE_ORDER = [
        'A' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5,
        '6' => 6, '7' => 7, '8' => 8, '9' => 9, '10' => 10,
        'J' => 11, 'Q' => 12, 'K' => 13
    ];

    /**
     * Sorterar en lista av kort i stigande färg- och värdeordning.
     *
     * @param Card[] $cards Lista med kort att sortera.
     * @return Card[] Sorterad lista med kort.
     */
    public function sortCards(array $cards): array
    {
        usort($cards, fn (Card $a, Card $b) =>
            self::SUIT_ORDER[$a->getSuit()] <=> self::SUIT_ORDER[$b->getSuit()]
            ?: self::VALUE_ORDER[$a->getValue()] <=> self::VALUE_ORDER[$b->getValue()]
        );

        return $cards;
    }

    /**
     * Sorterar en lista av kort i fallande färg- och värdeordning.
     *
     * @param Card[] $cards Lista med kort att sortera.
     * @return Card[] Sorterad lista i omvänd ordning.
     */
    public function sortCardsDescending(array $cards): array
    {
        usort($cards, fn (Card $a, Card $b) =>
            self::SUIT_ORDER[$b->getSuit()] <=> self::SUIT_ORDER[$a->getSuit()]
            ?: self::VALUE_ORDER[$b->getValue()] <=> self::VALUE_ORDER[$a->getValue()]
        );

        return $cards;
    }
}

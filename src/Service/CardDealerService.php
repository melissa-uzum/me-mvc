<?php

namespace App\Service;

use App\Card\CardHand;
use App\Card\DeckOfCards;

/**
 * Tjänst för att dela ut kort till spelare.
 */
class CardDealerService
{
    /**
     * Delar ut ett antal kort till ett antal spelare.
     *
     * @param DeckOfCards $deck Kortleken som används.
     * @param int $players Antal spelare.
     * @param int $cards Antal kort per spelare.
     * @return CardHand[] Lista med spelhänder.
     */
    public function deal(DeckOfCards $deck, int $players, int $cards): array
    {
        $hands = [];

        for ($i = 0; $i < $players; $i++) {
            $hands[] = $this->dealHand($deck, $cards);
        }

        return $hands;
    }

    /**
     * Delar ut en enskild hand med ett givet antal kort.
     *
     * @param DeckOfCards $deck Kortleken som används.
     * @param int $cards Antal kort att dra.
     * @return CardHand En komplett hand.
     */
    private function dealHand(DeckOfCards $deck, int $cards): CardHand
    {
        $hand = new CardHand();

        for ($j = 0; $j < $cards; $j++) {
            $hand->add($deck->drawOne());
        }

        return $hand;
    }
}

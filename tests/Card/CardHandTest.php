<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Card;
use App\Card\CardHand;

/**
 * Testklass för CardHand.
 */
class CardHandTest extends TestCase
{
    /**
     * Testar att kort kan läggas till och hämtas korrekt från handen.
     */
    public function testAddAndGetCards(): void
    {
        $hand = new CardHand();
        $card1 = new Card('♥', '7');
        $card2 = new Card('♠', 'K');

        $hand->add($card1);
        $hand->add($card2);

        $cards = $hand->getCards();

        $this->assertCount(2, $cards);
        $this->assertSame($card1, $cards[0]);
        $this->assertSame($card2, $cards[1]);
    }
}

<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Card;

/**
 * Testklass för Card.
 */
class CardTest extends TestCase
{
    /**
     * Testar att getSuit() och getValue() returnerar rätt värden.
     */
    public function testGetSuitAndValue(): void
    {
        $card = new Card('♥', '10');
        $this->assertEquals('♥', $card->getSuit());
        $this->assertEquals('10', $card->getValue());
    }

    /**
     * Testar att kortets strängrepresentation är korrekt.
     */
    public function testToString(): void
    {
        $card = new Card('♠', 'K');
        $this->assertEquals('K♠', (string)$card);
    }

    /**
     * Testar numeriskt värde för ett vanligt sifferkort.
     */
    public function testGetNumericValueNumber(): void
    {
        $card = new Card('♦', '7');
        $this->assertEquals(7, $card->getNumericValue());
    }

    /**
     * Testar numeriskt värde för klädda kort (J, Q, K).
     */
    public function testGetNumericValueFaceCards(): void
    {
        $this->assertEquals(11, (new Card('♣', 'J'))->getNumericValue());
        $this->assertEquals(12, (new Card('♣', 'Q'))->getNumericValue());
        $this->assertEquals(13, (new Card('♣', 'K'))->getNumericValue());
    }

    /**
     * Testar numeriskt värde för ess.
     */
    public function testGetNumericValueAce(): void
    {
        $card = new Card('♦', 'A');
        $this->assertEquals(1, $card->getNumericValue());
    }

    /**
     * Testar att ett ess identifieras korrekt.
     */
    public function testIsAceTrue(): void
    {
        $card = new Card('♥', 'A');
        $this->assertTrue($card->isAce());
    }

    /**
     * Testar att ett vanligt kort inte identifieras som ess.
     */
    public function testIsAceFalse(): void
    {
        $card = new Card('♥', '2');
        $this->assertFalse($card->isAce());
    }

    /**
     * Testar att sorteringsordningen beräknas korrekt.
     */
    public function testGetSortOrder(): void
    {
        $card = new Card('♠', '10');
        $this->assertEquals(410, $card->getSortOrder());
    }
}

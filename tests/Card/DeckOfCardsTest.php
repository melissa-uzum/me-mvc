<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\DeckOfCards;
use App\Card\Card;

/**
 * Testklass för DeckOfCards.
 */
class DeckOfCardsTest extends TestCase
{
    /**
     * Testar att en ny kortlek innehåller 52 kort.
     */
    public function testDeckHas52CardsOnInit(): void
    {
        $deck = new DeckOfCards(false);
        $this->assertCount(52, $deck->getCards());
    }

    /**
     * Testar att kortleken blandas så att ordningen blir annorlunda.
     */
    public function testShuffleRandomizesDeck(): void
    {
        $deck1 = new DeckOfCards(false);
        $deck2 = new DeckOfCards(false);

        $deck2->shuffle();

        $this->assertNotEquals(
            array_map(fn($c) => (string)$c, $deck1->getCards()),
            array_map(fn($c) => (string)$c, $deck2->getCards())
        );
    }

    /**
     * Testar att draw() returnerar rätt antal kort och minskar kortleken.
     */
    public function testDrawRemovesCards(): void
    {
        $deck = new DeckOfCards(false);
        $drawn = $deck->draw(5);

        $this->assertCount(5, $drawn);
        $this->assertCount(47, $deck->getCards());
    }

    /**
     * Testar att drawOne() returnerar ett kort och minskar kortleken.
     */
    public function testDrawOneReturnsCard(): void
    {
        $deck = new DeckOfCards(false);
        $card = $deck->drawOne();

        $this->assertInstanceOf(Card::class, $card);
        $this->assertCount(51, $deck->getCards());
    }

    /**
     * Testar att reset() återställer kortleken till 52 kort.
     */
    public function testResetResetsDeck(): void
    {
        $deck = new DeckOfCards(false);
        $deck->draw(52);

        $this->assertCount(0, $deck->getCards());

        $deck->reset();

        $this->assertCount(52, $deck->getCards());
    }

    /**
     * Testar att drawOne() automatiskt återställer kortleken om den är tom.
     */
    public function testDrawOneResetsAutomaticallyIfEmpty(): void
    {
        $deck = new DeckOfCards(false);
        $deck->draw(52);
        $card = $deck->drawOne();

        $this->assertInstanceOf(Card::class, $card);
        $this->assertLessThan(52, $deck->count());
    }
}

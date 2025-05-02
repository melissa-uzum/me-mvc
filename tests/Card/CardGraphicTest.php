<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\CardGraphic;

/**
 * Testklass för CardGraphic.
 */
class CardGraphicTest extends TestCase
{
    /**
     * Testar att rätt HTML genereras beroende på färg.
     */
    public function testGetUnicodeReturnsExpectedHtml(): void
    {
        $cardRed = new CardGraphic('♥', 'Q');
        $cardBlack = new CardGraphic('♠', 'K');

        $this->assertEquals('<span class="red-card">[Q♥]</span>', $cardRed->getUnicode());
        $this->assertEquals('<span class="black-card">[K♠]</span>', $cardBlack->getUnicode());
    }
}

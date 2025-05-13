<?php

namespace App\Tests\Game;

use PHPUnit\Framework\TestCase;
use App\Game\Game21;

/**
 * Enhetstester för Game21-klassen.
 */
class Game21Test extends TestCase
{
    /**
     * Testar att startpengar är korrekt satta till 100.
     */
    public function testInitialMoneyIs100(): void
    {
        $game = new Game21();
        $this->assertEquals(100, $game->getPlayerMoney());
        $this->assertEquals(100, $game->getBankMoney());
    }

    /**
     * Testar att en giltig insats registreras korrekt.
     */
    public function testPlaceValidBet(): void
    {
        $game = new Game21();
        $game->placeBet(10);
        $this->assertEquals(10, $game->getBet());
    }

    /**
     * Testar att ogiltig insats kastar undantag.
     */
    public function testPlaceInvalidBetThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $game = new Game21();
        $game->placeBet(200);
    }

    /**
     * Testar att ett kort läggs till spelarens hand.
     */
    public function testPlayerDrawAddsCard(): void
    {
        $game = new Game21();
        $game->playerDraw();
        $this->assertCount(1, $game->getPlayerHand()->getCards());
    }

    /**
     * Testar att ess-värde kan sättas manuellt.
     */
    public function testSetAceValue(): void
    {
        $game = new Game21();
        $game->playerDraw();

        $index = array_key_first($game->getAceChoices());
        if ($index !== null) {
            $game->setAceValue($index, 14);
            $this->assertEquals(14, $game->getAceChoices()[$index]);
        } else {
            $this->assertTrue(true);
        }
    }

    /**
     * Testar att spelarens stå-status ändras.
     */
    public function testPlayerStandsFlag(): void
    {
        $game = new Game21();
        $this->assertFalse($game->isPlayerStanding());
        $game->playerStands();
        $this->assertTrue($game->isPlayerStanding());
    }

    /**
     * Testar att ny runda återställer tillstånd korrekt.
     */
    public function testStartNewRoundResetsState(): void
    {
        $game = new Game21();
        $game->placeBet(10);
        $game->playerDraw();
        $game->playerStands();
        $game->startNewRound();

        $this->assertEquals(0, $game->getBet());
        $this->assertFalse($game->isPlayerStanding());
        $this->assertCount(0, $game->getPlayerHand()->getCards());
    }

    /**
     * Testar att pengar uppdateras korrekt efter vinst/förlust.
     */
    public function testApplyResultUpdatesMoney(): void
    {
        $game = new Game21();
        $game->placeBet(10);
        $game->playerStands();

        $reflection = new \ReflectionClass($game);
        $bankProp = $reflection->getProperty('bank');
        $bankProp->setAccessible(true);
        $bank = $bankProp->getValue($game);
        $bank->add(new \App\Card\Card('♠', '2'));

        $game->applyResult();

        $this->assertEquals(200, $game->getPlayerMoney() + $game->getBankMoney());
    }

    /**
     * Testar att rätt sträng returneras baserat på vinnaree.
     */
    public function testGetWinnerStringReturnsExpected(): void
    {
        $game = new Game21();
        $this->assertEquals("Spelet pågår...", $game->getWinnerString());

        $reflection = new \ReflectionClass($game);
        $standProp = $reflection->getProperty('playerStands');
        $standProp->setAccessible(true);
        $standProp->setValue($game, true);

        $this->assertStringContainsString('vinner', $game->getWinnerString());
    }

    /**
     * Testar att rätt array returneras för spelare och bank.
     */
    public function testGetPlayerAndBankValuesReturnsArray(): void
    {
        $game = new Game21();
        $values = $game->getPlayerAndBankValues();

        $this->assertArrayHasKey('player', $values);
        $this->assertArrayHasKey('bank', $values);
        $this->assertIsInt($values['player']);
        $this->assertIsInt($values['bank']);
    }
}

<?php

namespace App\Game;

use App\Card\DeckOfCards;
use App\Card\CardHand;
use App\Game\GameResultService;

/**
 * Hanterar spelet 21 (Blackjack-variant) mellan spelare och bank.
 */
class Game21
{
    private DeckOfCards $deck;
    private GamePlayer $player;
    private GameBank $bank;
    private GameBet $bet;
    private GameState $state;
    private GameRules $rules;

    /**
     * Skapar ett nytt spel och blandar kortleken.
     */
    public function __construct()
    {
        $this->deck = new DeckOfCards(true);
        $this->deck->shuffle();

        $this->player = new GamePlayer();
        $this->bank = new GameBank();
        $this->bet = new GameBet();
        $this->state = new GameState();
        $this->rules = new GameRules();
    }

    /**
     * Startar en ny runda och återställer alla tillstånd.
     */
    public function startNewRound(): void
    {
        $this->initDeck();
        $this->player->resetHand();
        $this->bank->resetHand();
        $this->state->reset();
        $this->bet->reset();
    }

    /**
     * Låter spelaren placera en insats.
     *
     * @param int $amount Belopp att satsa.
     * @throws \InvalidArgumentException Om insatsen är ogiltig.
     */
    public function placeBet(int $amount): void
    {
        $this->bet->placeBet($amount, $this->player->getWallet());
    }

    /**
     * Returnerar aktuell insats.
     */
    public function getBet(): int
    {
        return $this->bet->getBet();
    }

    /**
     * Returnerar spelarens saldo.
     */
    public function getPlayerMoney(): int
    {
        return $this->player->getMoney();
    }

    /**
     * Returnerar bankens saldo.
     */
    public function getBankMoney(): int
    {
        return $this->bank->getMoney();
    }

    /**
     * Tillämpa rundans resultat och justera saldon.
     */
    public function applyResult(): void
    {
        if ($this->state->isRoundOver()) {
            return;
        }

        $winner = $this->getWinner();
        $this->bet->applyResult($winner, $this->player->getWallet(), $this->bank->getWallet());
        $this->state->endRound();
    }


    /**
     * Returnerar true om någon spelare har 0 kronor.
     */
    public function isMatchOver(): bool
    {
        return $this->player->getMoney() <= 0 || $this->bank->getMoney() <= 0;
    }

    /**
     * Låter spelaren dra ett kort. Om spelaren går över 21 avslutas rundan.
     */
    public function playerDraw(): void
    {
        $card = $this->deck->drawOne();
        $this->player->addCard($card);

        if ($this->player->getValue() > 21) {
            $this->state->setPlayerStands();
            $this->applyResult();
        }
    }


    /**
     * Sätter essets värde (1 eller 14) i spelarens hand.
     *
     * @param int $index Index i handen.
     * @param int $value Antingen 1 eller 14.
     */

    public function setAceValue(int $index, int $value): void
    {
        $this->player->setAceValue($index, $value);

        if ($this->player->getValue() > 21) {
            $this->playerStands();
            $this->applyResult();
        }
    }


    /**
     * Returnerar valda ess-värden.
     *
     * @return array<int, int|null>
     */
    public function getAceChoices(): array
    {
        return $this->player->getAceChoices();
    }

    /**
     * Markerar att spelaren valt att stanna.
     */
    public function playerStands(): void
    {
        $this->state->setPlayerStands();
    }

    /**
     * Returnerar om spelaren har valt att stanna.
     */
    public function isPlayerStanding(): bool
    {
        return $this->state->hasPlayerStood();
    }

    /**
     * Låter banken dra kort tills den når minst 17 poäng.
     */
    public function bankTurn(): void
    {
        while ($this->bank->getValue() < 17) {
            $this->bank->addCard($this->deck->drawOne());
        }
    }

    /**
     * Returnerar spelarens hand.
     */
    public function getPlayerHand(): CardHand
    {
        return $this->player->getHand();
    }

    /**
     * Returnerar bankens hand.
     */
    public function getBankHand(): CardHand
    {
        return $this->bank->getHand();
    }

    /**
     * Returnerar spelarens totala poäng.
     */
    public function getPlayerValue(): int
    {
        return $this->player->getValue();
    }

    /**
     * Returnerar bankens totala poäng.
     */
    public function getBankValue(): int
    {
        return $this->bank->getValue();
    }

    /**
     * Returnerar true om spelet är över.
     */
    public function isGameOver(): bool
    {
        return $this->rules->isGameOver(
            $this->player->getHand(),
            $this->bank->getHand(),
            $this->state->hasPlayerStood(),
            $this->player->getAceChoices()
        );
    }

    /**
     * Returnerar vinnaren av rundan: 'player', 'bank' eller 'none'.
     */
    public function getWinner(): string
    {
        return $this->rules->getWinner(
            $this->player->getHand(),
            $this->bank->getHand(),
            $this->player->getAceChoices()
        );
    }

    /**
     * Returnerar ett meddelande om vem som vann.
     */
    public function getWinnerString(): string
    {
        $resultService = new GameResultService();
        return $resultService->getResultMessage(
            $this->isGameOver(),
            $this->getWinner()
        );
    }

    /**
     * Returnerar poängen för både spelare och bank.
     *
     * @return array{player: int, bank: int}
     */
    public function getPlayerAndBankValues(): array
    {
        return [
            'player' => $this->getPlayerValue(),
            'bank' => $this->getBankValue(),
        ];
    }

    /**
     * Återställer kortleken.
     */
    private function initDeck(): void
    {
        $this->deck = new DeckOfCards(true);
        $this->deck->shuffle();
    }

    /**
     * Returnerar den senaste insatsen.
     */
    public function getLastBet(): int
    {
        return $this->getBet();
    }
}

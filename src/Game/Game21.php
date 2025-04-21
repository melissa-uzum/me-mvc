<?php

namespace App\Game;

use App\Card\CardHand;
use App\Card\DeckOfCards;

class Game21
{
    private DeckOfCards $deck;
    private CardHand $player;
    private CardHand $bank;
    private bool $playerStands = false;
    private bool $roundOver = false;

    private int $playerMoney = 100;
    private int $bankMoney = 100;
    private int $bet = 0;

    private array $aceChoices = [];

    public function __construct()
    {
        $this->deck = new DeckOfCards();
        $this->deck->shuffle();
        $this->player = new CardHand();
        $this->bank = new CardHand();
    }

    public function startNewRound(): void
    {
        $this->deck->shuffle();
        $this->player = new CardHand();
        $this->bank = new CardHand();
        $this->playerStands = false;
        $this->roundOver = false;
        $this->aceChoices = [];
    }

    public function placeBet(int $amount): void
    {
        if ($amount > $this->playerMoney || $amount > $this->bankMoney || $amount <= 0) {
            throw new \InvalidArgumentException('Ogiltig insats.');
        }

        $this->bet = $amount;
    }

    public function getBet(): int
    {
        return $this->bet;
    }

    public function getPlayerMoney(): int
    {
        return $this->playerMoney;
    }

    public function getBankMoney(): int
    {
        return $this->bankMoney;
    }

    public function applyResult(): void
    {
        if ($this->roundOver) {
            return;
        }

        $winner = $this->getWinner();

        if ('player' === $winner) {
            $this->playerMoney += $this->bet;
            $this->bankMoney -= $this->bet;
        } elseif ('bank' === $winner) {
            $this->playerMoney -= $this->bet;
            $this->bankMoney += $this->bet;
        }

        $this->bet = 0;
        $this->roundOver = true;
    }

    public function isMatchOver(): bool
    {
        return $this->playerMoney <= 0 || $this->bankMoney <= 0;
    }

    public function playerDraw(): void
    {
        $card = $this->deck->drawOne();
        $this->player->add($card);

        if ($card->isAce()) {
            $index = count($this->player->getCards()) - 1;
            if (!isset($this->aceChoices[$index])) {
                $this->aceChoices[$index] = null;
            }
        }
    }

    public function setAceValue(int $index, int $value): void
    {
        if (!in_array($value, [1, 14])) {
            return;
        }

        if (isset($this->aceChoices[$index])) {
            $this->aceChoices[$index] = $value;
        }
    }

    public function getAceChoices(): array
    {
        return $this->aceChoices;
    }

    public function playerStands(): void
    {
        $this->playerStands = true;
    }

    public function isPlayerStanding(): bool
    {
        return $this->playerStands;
    }

    public function bankTurn(): void
    {
        while ($this->getBankValue() < 17) {
            $this->bank->add($this->deck->drawOne());
        }
    }

    public function getPlayerHand(): CardHand
    {
        return $this->player;
    }

    public function getBankHand(): CardHand
    {
        return $this->bank;
    }

    public function getPlayerValue(): int
    {
        return $this->calculateValue($this->player, true);
    }

    public function getBankValue(): int
    {
        return $this->calculateValue($this->bank, false);
    }

    public function isGameOver(): bool
    {
        if ($this->getPlayerValue() > 21) {
            return true;
        }

        if ($this->playerStands && $this->getBankValue() > 0) {
            return true;
        }

        return false;
    }

    public function getWinner(): string
    {
        if ($this->getPlayerValue() > 21) {
            return 'bank';
        }

        if ($this->getBankValue() > 21) {
            return 'player';
        }

        if ($this->playerStands) {
            if ($this->getBankValue() >= $this->getPlayerValue()) {
                return 'bank';
            } else {
                return 'player';
            }
        }

        return 'none';
    }

    private function calculateValue(CardHand $hand, bool $isPlayer): int
    {
        $total = 0;

        foreach ($hand->getCards() as $index => $card) {
            if ($card->isAce() && $isPlayer) {
                if (isset($this->aceChoices[$index]) && in_array($this->aceChoices[$index], [1, 14])) {
                    $total += $this->aceChoices[$index];
                }
            } elseif ($card->isAce()) {
                $total += ($total + 14 <= 21) ? 14 : 1;
            } else {
                $total += $card->getNumericValue();
            }
        }

        return $total;
    }
}

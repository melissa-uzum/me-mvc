<?php

namespace App\Game;

use App\Card\CardHand;
use App\Card\DeckOfCards;

/**
 * Hanterar spelet 21 (Blackjack-variant) mellan spelare och bank.
 */
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

    /**
     * Skapar ett nytt spel och blandar kortleken.
     */
    public function __construct()
    {
        $this->deck = new DeckOfCards(true);
        $this->deck->shuffle();
        $this->player = new CardHand();
        $this->bank = new CardHand();
    }

    /**
     * Startar en ny runda och återställer rundans tillstånd.
     */
    public function startNewRound(): void
    {
        $this->deck = new DeckOfCards(true);
        $this->deck->shuffle();
        $this->player = new CardHand();
        $this->bank = new CardHand();
        $this->playerStands = false;
        $this->roundOver = false;
        $this->aceChoices = [];
        $this->bet = 0;
    }

    /**
     * Låter spelaren placera en insats.
     *
     * @param int $amount Belopp att satsa.
     *
     * @throws \InvalidArgumentException Om insatsen är ogiltig.
     */
    public function placeBet(int $amount): void
    {
        if ($amount > $this->playerMoney || $amount > $this->bankMoney || $amount <= 0) {
            throw new \InvalidArgumentException('Ogiltig insats.');
        }
        $this->bet = $amount;
    }

    /**
     * Returnerar aktuell insats.
     */
    public function getBet(): int
    {
        return $this->bet;
    }

    /**
     * Returnerar spelarens pengar.
     */
    public function getPlayerMoney(): int
    {
        return $this->playerMoney;
    }

    /**
     * Returnerar bankens pengar.
     */
    public function getBankMoney(): int
    {
        return $this->bankMoney;
    }

    /**
     * Tillämpa rundans resultat och justera pengar.
     */
    public function applyResult(): void
    {
        if ($this->roundOver) {
            return;
        }

        $winner = $this->getWinner();

        if ($winner === 'player') {
            $this->playerMoney += $this->bet;
            $this->bankMoney -= $this->bet;
        } elseif ($winner === 'bank') {
            $this->playerMoney -= $this->bet;
            $this->bankMoney += $this->bet;
        }

        $this->bet = 0;
        $this->roundOver = true;
    }

    /**
     * Kollar om matchen är över (någon har 0 pengar).
     */
    public function isMatchOver(): bool
    {
        return $this->playerMoney <= 0 || $this->bankMoney <= 0;
    }

    /**
     * Låter spelaren dra ett kort.
     */
    public function playerDraw(): void
    {
        $card = $this->deck->drawOne();
        $this->player->add($card);

        if ($card->isAce()) {
            $index = count($this->player->getCards()) - 1;
            if (!array_key_exists($index, $this->aceChoices)) {
                $this->aceChoices[$index] = null;
            }
        }
    }

    /**
     * Sätter värdet för ett ess i spelarens hand.
     *
     * @param int $index Index för kortet.
     * @param int $value Antingen 1 eller 14.
     */
    public function setAceValue(int $index, int $value): void
    {
        if (!in_array($value, [1, 14])) {
            return;
        }

        $card = $this->player->getCards()[$index] ?? null;
        if ($card && $card->isAce()) {
            $this->aceChoices[$index] = $value;
        }
    }

    /**
     * Returnerar valda ess-värden.
     */
    public function getAceChoices(): array
    {
        return $this->aceChoices;
    }

    /**
     * Markerar att spelaren stannar.
     */
    public function playerStands(): void
    {
        $this->playerStands = true;
    }

    /**
     * Returnerar om spelaren har stannat.
     */
    public function isPlayerStanding(): bool
    {
        return $this->playerStands;
    }

    /**
     * Låter banken dra kort tills värdet är minst 17.
     */
    public function bankTurn(): void
    {
        while ($this->getBankValue() < 17) {
            $this->bank->add($this->deck->drawOne());
        }
    }

    /**
     * Returnerar spelarens hand.
     */
    public function getPlayerHand(): CardHand
    {
        return $this->player;
    }

    /**
     * Returnerar bankens hand.
     */
    public function getBankHand(): CardHand
    {
        return $this->bank;
    }

    /**
     * Beräknar spelarens totala poäng.
     */
    public function getPlayerValue(): int
    {
        return $this->calculateValue($this->player, true);
    }

    /**
     * Beräknar bankens totala poäng.
     */
    public function getBankValue(): int
    {
        return $this->calculateValue($this->bank, false);
    }

    /**
     * Returnerar om spelet är slut (spelare stannar eller någon går över 21).
     */
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

    /**
     * Returnerar vem som vinner rundan: 'player', 'bank' eller 'none'.
     */
    public function getWinner(): string
    {
        if ($this->getPlayerValue() > 21) {
            return 'bank';
        }

        if ($this->getBankValue() > 21) {
            return 'player';
        }

        if ($this->playerStands) {
            return $this->getBankValue() >= $this->getPlayerValue() ? 'bank' : 'player';
        }

        return 'none';
    }

    /**
     * Intern metod för att räkna ut en hands poäng, hanterar ess.
     *
     * @param CardHand $hand Handen som ska värderas.
     * @param bool $isPlayer Om handen tillhör spelaren (för val av ess-värde).
     *
     * @return int Det totala värdet.
     */
    private function calculateValue(CardHand $hand, bool $isPlayer): int
    {
        $total = 0;

        foreach ($hand->getCards() as $index => $card) {
            if ($card->isAce() && $isPlayer) {
                if (isset($this->aceChoices[$index])) {
                    $total += $this->aceChoices[$index];
                } else {
                    continue;
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

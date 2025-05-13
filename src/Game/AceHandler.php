<?php

namespace App\Game;

use App\Card\Card;
use App\Card\CardHand;

/**
 * Hanterar val av ess-värden i spelarens hand.
 */
final class AceHandler
{
    /**
     * Sparar valda värden för varje ess i handen.
     *
     * @var array<int, int|null>
     */
    private array $aceChoices = [];

    /**
     * Identifierar om det senaste kortet i handen är ett ess och registrerar det.
     *
     * @param CardHand $hand Spelarens aktuella hand.
     */
    public function track(CardHand $hand): void
    {
        $cards = $hand->getCards();
        $index = count($cards) - 1;
        $card = $cards[$index] ?? null;

        if ($card instanceof Card && $card->isAce()) {
            $this->trackAce($index);
        }
    }

    /**
     * Registrerar ett ess vid en specifik position.
     *
     * @param int $index Positionen i kortleken.
     */
    public function trackAce(int $index): void
    {
        if (!array_key_exists($index, $this->aceChoices)) {
            $this->aceChoices[$index] = null;
        }
    }

    /**
     * Sätter spelarens valda värde (1 eller 14) för ett ess vid en given position.
     *
     * @param int $index Positionen i handen.
     * @param int $value Valda värdet för esset.
     * @param CardHand $hand Spelarens aktuella hand.
     */
    public function setAceValue(int $index, int $value, CardHand $hand): void
    {
        $card = $hand->getCards()[$index] ?? null;

        if ($card instanceof Card && $card->isAce() && in_array($value, [1, 14], true)) {
            $this->aceChoices[$index] = $value;
        }
    }

    /**
     * Hämtar valt värde för ett ess.
     *
     * @param int $index Positionen i handen.
     * @return int|null Valda värdet eller null om inget valts.
     */
    public function getAceValue(int $index): ?int
    {
        return $this->aceChoices[$index] ?? null;
    }

    /**
     * Returnerar alla valda ess-värden.
     *
     * @return array<int, int|null> Array med index och valda värden.
     */
    public function getAll(): array
    {
        return $this->aceChoices;
    }

    /**
     * Nollställer alla val för ess.
     */
    public function reset(): void
    {
        $this->aceChoices = [];
    }
}

<?php

namespace App\Service;

use App\Card\DeckOfCards;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Tjänst för att hantera kortlekens tillstånd i sessionen.
 */
class DeckService
{
    private SessionInterface $session;
    private const DECK_KEY = 'deck';

    /**
     * Konstruktor som injicerar sessionen.
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Hämtar kortleken från sessionen eller skapar en ny blandad kortlek.
     */
    public function getDeck(): DeckOfCards
    {
        $deck = $this->session->get(self::DECK_KEY);

        if (!$deck instanceof DeckOfCards) {
            $deck = new DeckOfCards(true);
            $this->session->set(self::DECK_KEY, $deck);
        }

        return $deck;
    }

    /**
     * Sparar en ny kortlek i sessionen.
     */
    public function setDeck(DeckOfCards $deck): void
    {
        $this->session->set(self::DECK_KEY, $deck);
    }

    /**
     * Återställer kortleken genom att skapa en ny och blanda den.
     */
    public function resetDeck(): DeckOfCards
    {
        $deck = new DeckOfCards(true);
        $deck->shuffle();
        $this->setDeck($deck);

        return $deck;
    }
}

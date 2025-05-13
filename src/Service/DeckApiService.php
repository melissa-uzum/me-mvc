<?php

namespace App\Service;

use App\Card\DeckOfCards;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Tjänst för att hantera kortlek via session för API-anrop.
 */
class DeckApiService
{
    private const DECK_KEY = 'deck';

    private SessionInterface $session;

    /**
     * Konstruktor som hämtar session via RequestStack.
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
    }

    /**
     * Hämtar kortleken från sessionen eller skapar en ny.
     */
    public function getDeck(): DeckOfCards
    {
        return $this->session->get(self::DECK_KEY) ?? new DeckOfCards();
    }

    /**
     * Sparar kortleken i sessionen.
     */
    public function saveDeck(DeckOfCards $deck): void
    {
        $this->session->set(self::DECK_KEY, $deck);
    }

    /**
     * Omvandlar en lista av kort till strängrepresentation.
     *
     * @param array<int, mixed> $cards Kort att omvandla.
     * @return string[] Lista med kort i strängformat.
     */
    public function stringifyCards(array $cards): array
    {
        return array_map(fn ($card) => (string) $card, $cards);
    }
}

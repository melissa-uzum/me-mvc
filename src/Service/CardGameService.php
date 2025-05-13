<?php

namespace App\Service;

use App\Card\DeckOfCards;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Tjänst för att hantera kortspel via session.
 */
class CardGameService
{
    private const DECK_KEY = 'deck';

    private SessionInterface $session;
    private CardUtilityService $cardUtils;
    private CardDealerService $dealer;

    public function __construct(
        RequestStack $requestStack,
        CardUtilityService $cardUtils,
        CardDealerService $dealer
    ) {
        $this->session = $requestStack->getSession();
        $this->cardUtils = $cardUtils;
        $this->dealer = $dealer;
    }

    /**
     * Returnerar kortleken sorterad.
     *
     * @return array Sorterad lista av kort.
     */
    public function getSortedDeck(): array
    {
        return $this->cardUtils->sortCards($this->getDeck()->getCards());
    }

    /**
     * Blandar en ny kortlek och sparar den.
     *
     * @return array Blandad kortlek.
     */
    public function shuffleDeck(): array
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $this->saveDeck($deck);
        return $deck->getCards();
    }

    /**
     * Drar ett antal kort från kortleken.
     *
     * @param int $count Antal kort att dra.
     * @return array Dragna kort.
     */
    public function drawCards(int $count = 1): array
    {
        $deck = $this->getDeck();
        $drawn = $deck->draw($count);
        $this->saveDeck($deck);
        return $drawn;
    }

    /**
     * Delar ut kort till spelare.
     *
     * @param int $players Antal spelare.
     * @param int $cards Antal kort per spelare.
     * @return array|null Lista med spelhänder eller null om korten inte räcker.
     */
    public function dealToPlayers(int $players, int $cards): ?array
    {
        $deck = $this->getDeck();
        if ($deck->count() < $players * $cards) {
            return null;
        }

        $hands = $this->dealer->deal($deck, $players, $cards);
        $this->saveDeck($deck);
        return $hands;
    }

    /**
     * Returnerar antal kort kvar i leken.
     */
    public function getDeckCount(): int
    {
        return $this->getDeck()->count();
    }

    /**
     * Hämtar aktuell kortlek från session eller skapar ny.
     */
    private function getDeck(): DeckOfCards
    {
        return $this->session->get(self::DECK_KEY) ?? new DeckOfCards();
    }

    /**
     * Sparar kortleken i sessionen.
     */
    private function saveDeck(DeckOfCards $deck): void
    {
        $this->session->set(self::DECK_KEY, $deck);
    }
}

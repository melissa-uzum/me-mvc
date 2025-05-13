<?php

namespace App\Controller\Api;

use App\Service\Deck\GetDeckResponder;
use App\Service\Deck\ShuffleDeckResponder;
use App\Service\Deck\DrawOneCardResponder;
use App\Service\Deck\DrawNumberResponder;
use App\Service\Deck\DealCardsResponder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * API-kontroller för kortleksoperationer.
 *
 * Tillhandahåller endpoints för att hämta, blanda, dra och dela ut kort.
 */

#[Route('/api/deck')]
class ApiDeckAction extends AbstractController
{
    public function __construct(
        private GetDeckResponder $getDeck,
        private ShuffleDeckResponder $shuffleDeck,
        private DrawOneCardResponder $drawOne,
        private DrawNumberResponder $drawNumber,
        private DealCardsResponder $dealCards
    ) {
    }

    /**
     * Returnerar aktuell kortlek, sorterad.
     *
     * @return JsonResponse JSON-svar med alla kort och antal kvar.
     */
    #[Route('', name: 'api_deck', methods: ['GET'])]
    public function deck(): JsonResponse
    {
        return ($this->getDeck)();
    }

    /**
     * Blandar en ny kortlek och sparar den i sessionen.
     *
     * @return JsonResponse JSON-svar med blandad kortlek.
     */
    #[Route('/shuffle', name: 'api_deck_shuffle', methods: ['GET', 'POST'])]
    public function shuffle(): JsonResponse
    {
        return ($this->shuffleDeck)();
    }

    /**
     * Drar ett kort från den aktuella kortleken.
     *
     * @return JsonResponse JSON-svar med ett draget kort och antal kvar.
     */
    #[Route('/draw', name: 'api_draw_one', methods: ['GET', 'POST'])]
    public function drawOne(): JsonResponse
    {
        return ($this->drawOne)();
    }

    /**
     * Drar ett angivet antal kort från kortleken.
     *
     * @param int $number Antal kort att dra.
     * @return JsonResponse JSON-svar med dragna kort och antal kvar.
     */
    #[Route('/draw/{number<\d+>}', name: 'api_draw_number', methods: ['GET', 'POST'])]
    public function drawNumber(int $number): JsonResponse
    {
        return $this->drawNumber->draw($number);
    }

    /**
     * Delar ut ett antal kort till ett angivet antal spelare.
     *
     * @param int $players Antal spelare.
     * @param int $cards Antal kort per spelare.
     * @return JsonResponse JSON-svar med varje spelares hand och kort kvar i leken.
     */
    #[Route('/deal/{players<\d+>}/{cards<\d+>}', name: 'api_deal', methods: ['GET', 'POST'])]
    public function deal(int $players, int $cards): JsonResponse
    {
        return $this->dealCards->deal($players, $cards);
    }
}

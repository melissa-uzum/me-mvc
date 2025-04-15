<?php

namespace App\Controller;

use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ApiCardController extends AbstractController
{
    #[Route('/api/deck', name: 'api_deck', methods: ['GET'])]
    public function apiDeck(): JsonResponse
    {
        $deck = new DeckOfCards();
        $cards = array_map(fn ($card) => (string) $card, $deck->getCards());

        return $this->json([
            'deck' => $cards,
            'count' => count($cards),
        ]);
    }

    #[Route('/api/deck/shuffle', name: 'api_deck_shuffle', methods: ['POST'])]
    public function apiShuffle(SessionInterface $session): JsonResponse
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set('deck', $deck);

        $cards = array_map(fn ($card) => (string) $card, $deck->getCards());

        return $this->json([
            'shuffled_deck' => $cards,
            'count' => count($cards),
        ]);
    }

    #[Route('/api/deck/draw', name: 'api_draw_one', methods: ['POST'])]
    public function apiDrawOne(SessionInterface $session): JsonResponse
    {
        $deck = $session->get('deck') ?? new DeckOfCards();
        $drawn = $deck->draw(1);
        $session->set('deck', $deck);

        return $this->json([
            'drawn' => array_map(fn ($card) => (string) $card, $drawn),
            'remaining' => $deck->count(),
        ]);
    }

    #[Route('/api/deck/draw/{number<\d+>}', name: 'api_draw_number', methods: ['POST'])]
    public function apiDrawNumber(int $number, SessionInterface $session): JsonResponse
    {
        $deck = $session->get('deck') ?? new DeckOfCards();
        $count = min($number, $deck->count());
        $drawn = $deck->draw($count);
        $session->set('deck', $deck);

        return $this->json([
            'requested' => $number,
            'drawn' => array_map(fn ($card) => (string) $card, $drawn),
            'remaining' => $deck->count(),
        ]);
    }

    #[Route('/api/deck/deal/{players<\d+>}/{cards<\d+>}', name: 'api_deal', methods: ['POST'])]
    public function apiDeal(int $players, int $cards, SessionInterface $session): JsonResponse
    {
        $deck = $session->get('deck') ?? new DeckOfCards();

        $total = $players * $cards;
        if ($deck->count() < $total) {
            return $this->json([
                'error' => 'Inte tillräckligt med kort i leken',
                'remaining' => $deck->count(),
            ], 400);
        }

        $hands = [];
        for ($i = 1; $i <= $players; ++$i) {
            $hand = $deck->draw($cards);
            $hands["Spelare $i"] = array_map(fn ($card) => (string) $card, $hand);
        }

        $session->set('deck', $deck);

        return $this->json([
            'hands' => $hands,
            'remaining' => $deck->count(),
        ]);
    }
}

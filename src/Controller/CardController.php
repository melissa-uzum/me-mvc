<?php

namespace App\Controller;

use App\Service\CardGameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Kontroller som hanterar kortleksfunktioner: visa, blanda, dra, dela.
 */
class CardController extends AbstractController
{
    #[Route('/card', name: 'card_index')]
    public function index(): Response
    {
        return $this->render('card/index.html.twig');
    }

    #[Route('/card/deck', name: 'card_deck')]
    public function deck(CardGameService $cardGame): Response
    {
        return $this->render('card/deck.html.twig', [
            'cards' => $cardGame->getSortedDeck(),
        ]);
    }

    #[Route('/card/deck/shuffle', name: 'card_shuffle')]
    public function shuffle(CardGameService $cardGame): Response
    {
        return $this->render('card/shuffle.html.twig', [
            'cards' => $cardGame->shuffleDeck(),
        ]);
    }

    #[Route('/card/deck/draw', name: 'card_draw')]
    public function draw(CardGameService $cardGame): Response
    {
        return $this->render('card/draw.html.twig', [
            'drawn' => $cardGame->drawCards(1),
            'remaining' => $cardGame->getDeckCount(),
        ]);
    }

    #[Route('/card/deck/draw/{number<\d+>}', name: 'card_draw_number')]
    public function drawNumber(int $number, CardGameService $cardGame): Response
    {
        $drawn = $cardGame->drawCards($number);

        return $this->render('card/draw-number.html.twig', [
            'drawn' => $drawn,
            'requested' => $number,
            'drawn_count' => count($drawn),
            'remaining' => $cardGame->getDeckCount(),
        ]);
    }

    #[Route('/card/deck/deal/{players<\d+>}/{cards<\d+>}', name: 'card_deal')]
    public function deal(int $players, int $cards, CardGameService $cardGame): Response
    {
        $hands = $cardGame->dealToPlayers($players, $cards);

        if ($hands === null) {
            $this->addFlash('notice', "Inte tillräckligt med kort för att dela ut $cards kort till $players spelare.");
            return $this->redirectToRoute('card_index');
        }

        return $this->render('card/deal.html.twig', [
            'hands' => $hands,
            'players' => $players,
            'cards' => $cards,
            'remaining' => $cardGame->getDeckCount(),
        ]);
    }
}

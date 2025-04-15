<?php

namespace App\Controller;

use App\Card\CardHand;
use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CardController extends AbstractController
{
    #[Route('/card', name: 'card_index')]
    public function index(): Response
    {
        return $this->render('card/index.html.twig');
    }

    #[Route('/card/deck', name: 'card_deck')]
    public function deck(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $cards = $deck->getCards();
        $session->set('deck', $deck);

        return $this->render('card/deck.html.twig', [
            'cards' => $cards,
        ]);
    }

    #[Route('/card/deck/shuffle', name: 'card_shuffle')]
    public function shuffle(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set('deck', $deck);
        $cards = $deck->getCards();

        return $this->render('card/shuffle.html.twig', [
            'cards' => $cards,
        ]);
    }

    #[Route('/card/deck/draw', name: 'card_draw')]
    public function draw(SessionInterface $session): Response
    {
        $deck = $session->get('deck');

        if (!$deck instanceof DeckOfCards) {
            $deck = new DeckOfCards();
        }

        $drawn = $deck->draw(1);
        $session->set('deck', $deck);

        return $this->render('card/draw.html.twig', [
            'drawn' => $drawn,
            'remaining' => $deck->count(),
        ]);
    }

    #[Route('/card/deck/draw/{number<\d+>}', name: 'card_draw_number')]
    public function drawNumber(int $number, SessionInterface $session): Response
    {
        $deck = $session->get('deck');

        if (!$deck instanceof DeckOfCards) {
            $deck = new DeckOfCards();
        }

        $drawCount = min($number, $deck->count());
        $drawn = $deck->draw($drawCount);
        $session->set('deck', $deck);

        return $this->render('card/draw-number.html.twig', [
            'drawn' => $drawn,
            'requested' => $number,
            'drawn_count' => $drawCount,
            'remaining' => $deck->count(),
        ]);
    }

    #[Route('/session', name: 'session_view')]
    public function viewSession(SessionInterface $session): Response
    {
        $raw = $session->all();
        $all = [];

        foreach ($raw as $key => $value) {
            if (is_object($value)) {
                if (method_exists($value, '__toString')) {
                    $all[$key] = (string) $value;
                } elseif ('deck' === $key && method_exists($value, 'getCards')) {
                    $all[$key] = array_map(
                        fn ($card) => (string) $card,
                        $value->getCards()
                    );
                } else {
                    $all[$key] = 'Objekt av typen '.get_class($value);
                }
            } else {
                $all[$key] = $value;
            }
        }

        return $this->render('card/session.html.twig', [
            'session' => $all,
        ]);
    }

    #[Route('/session/delete', name: 'session_delete')]
    public function deleteSession(SessionInterface $session): Response
    {
        $session->clear();
        $this->addFlash('notice', 'Sessionen har rensats.');

        return $this->redirectToRoute('session_view');
    }

    #[Route('/card/deck/deal/{players<\d+>}/{cards<\d+>}', name: 'card_deal')]
    public function deal(int $players, int $cards, SessionInterface $session): Response
    {
        $deck = $session->get('deck');

        if (!$deck instanceof DeckOfCards) {
            $deck = new DeckOfCards();
        }

        $totalToDraw = $players * $cards;
        $available = $deck->count();

        if ($available < $totalToDraw) {
            $this->addFlash('notice', "Det finns bara $available kort kvar, kan inte dela ut $totalToDraw.");

            return $this->redirectToRoute('card_index');
        }

        $hands = [];

        for ($i = 0; $i < $players; ++$i) {
            $hand = new CardHand();
            for ($j = 0; $j < $cards; ++$j) {
                $card = $deck->draw(1)[0];
                $hand->add($card);
            }
            $hands[] = $hand;
        }

        $session->set('deck', $deck);

        return $this->render('card/deal.html.twig', [
            'hands' => $hands,
            'players' => $players,
            'cards' => $cards,
            'remaining' => $deck->count(),
        ]);
    }
}

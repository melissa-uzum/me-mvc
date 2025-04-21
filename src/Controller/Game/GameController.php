<?php

namespace App\Controller\Game;

use App\Game\Game21;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/game', name: 'game_start')]
    public function start(): Response
    {
        return $this->render('game/index.html.twig');
    }

    #[Route('/game/doc', name: 'game_doc')]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }

    #[Route('/game/play', name: 'game_play')]
    public function play(SessionInterface $session): Response
    {
        $game = $session->get('game21');

        if (!$game) {
            $game = new Game21();
            $session->set('game21', $game);
        }

        if ($game->isPlayerStanding() && !$game->isGameOver()) {
            $game->bankTurn();
        }

        if ($game->isGameOver()) {
            $game->applyResult();
        }

        $session->set('game21', $game);

        return $this->render('game/play.html.twig', [
            'playerHand' => $game->getPlayerHand()->getCards(),
            'bankHand' => $game->isPlayerStanding() || $game->isGameOver()
                ? $game->getBankHand()->getCards() : [],
            'playerValue' => $game->getPlayerValue(),
            'bankValue' => $game->isPlayerStanding() || $game->isGameOver()
                ? $game->getBankValue() : null,
            'gameOver' => $game->isGameOver(),
            'matchOver' => $game->isMatchOver(),
            'winner' => $game->getWinner(),
            'playerMoney' => $game->getPlayerMoney(),
            'bankMoney' => $game->getBankMoney(),
            'bet' => $game->getBet(),
            'aceChoices' => $game->getAceChoices(),
        ]);
    }

    #[Route('/game/draw', name: 'game_draw', methods: ['POST'])]
    public function draw(SessionInterface $session): Response
    {
        $game = $session->get('game21');
        $game->playerDraw();

        $session->set('game21', $game);

        return $this->redirectToRoute('game_play');
    }

    #[Route('/game/stand', name: 'game_stand', methods: ['POST'])]
    public function stand(SessionInterface $session): Response
    {
        $game = $session->get('game21');
        $game->playerStands();

        $session->set('game21', $game);

        return $this->redirectToRoute('game_play');
    }

    #[Route('/game/reset', name: 'game_reset', methods: ['POST'])]
    public function reset(SessionInterface $session): Response
    {
        $session->remove('game21');

        return $this->redirectToRoute('game_play');
    }

    #[Route('/game/bet', name: 'game_bet')]
    public function bet(SessionInterface $session, Request $request): Response
    {
        $game = $session->get('game21') ?? new Game21();

        if ($request->isMethod('POST')) {
            $bet = (int) $request->request->get('bet');

            try {
                $game->startNewRound();
                $game->placeBet($bet);
                $session->set('game21', $game);

                return $this->redirectToRoute('game_play');
            } catch (\InvalidArgumentException $e) {
                return $this->render('game/bet.html.twig', [
                    'game' => $game,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $this->render('game/bet.html.twig', [
            'game' => $game,
            'error' => null,
        ]);
    }

    #[Route('/game/ace', name: 'game_ace', methods: ['POST'])]
    public function ace(Request $request, SessionInterface $session): Response
    {
        $game = $session->get('game21');

        $index = (int) $request->request->get('index');
        $value = (int) $request->request->get('value');

        $game->setAceValue($index, $value);

        $session->set('game21', $game);

        return $this->redirectToRoute('game_play');
    }
}

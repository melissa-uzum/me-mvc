<?php

namespace App\Controller\Game;

use App\Game\Game21;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
        $game = $session->get('game21') ?? new Game21();
        $session->set('game21', $game);

        return $this->render('game/play.html.twig', [
            'playerHand' => $game->getPlayerHand()->getCards(),
            'bankHand' => $game->isPlayerStanding() || $game->isGameOver()
                ? $game->getBankHand()->getCards() : [],
            'playerValue' => $game->getPlayerValue(),
            'bankValue' => $game->isPlayerStanding() || $game->isGameOver()
                ? $game->getBankValue() : null,
            'gameOver' => $game->isGameOver(),
            'winner' => $game->getWinner(),
        ]);
    }

    #[Route('/game/draw', name: 'game_draw', methods: ['POST'])]
    public function draw(SessionInterface $session): Response
    {
        /** @var Game21 $game */
        $game = $session->get('game21');
        $game->playerDraw();

        $session->set('game21', $game);
        return $this->redirectToRoute('game_play');
    }

    #[Route('/game/stand', name: 'game_stand', methods: ['POST'])]
    public function stand(SessionInterface $session): Response
    {
        /** @var Game21 $game */
        $game = $session->get('game21');
        $game->playerStands();
        $game->bankTurn();

        $session->set('game21', $game);
        return $this->redirectToRoute('game_play');
    }

    #[Route('/game/reset', name: 'game_reset', methods: ['POST'])]
    public function reset(SessionInterface $session): Response
    {
        $session->remove('game21');
        return $this->redirectToRoute('game_play');
    }
}

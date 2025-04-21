<?php

namespace App\Controller\Game;

use App\Game\Game21;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class GameApiController extends AbstractController
{
    #[Route('/api/game', name: 'api_game', methods: ['GET'])]
    public function gameStatus(SessionInterface $session): JsonResponse
    {
        $game = $session->get('game21') ?? new Game21();

        return $this->json([
            'player' => [
                'hand' => array_map('strval', $game->getPlayerHand()->getCards()),
                'value' => $game->getPlayerValue(),
                'money' => $game->getPlayerMoney(),
            ],
            'bank' => [
                'hand' => $game->isPlayerStanding() || $game->isGameOver()
                    ? array_map('strval', $game->getBankHand()->getCards()) : [],
                'value' => $game->isPlayerStanding() || $game->isGameOver()
                    ? $game->getBankValue() : null,
                'money' => $game->getBankMoney(),
            ],
            'bet' => $game->getBet(),
            'gameOver' => $game->isGameOver(),
            'matchOver' => $game->isMatchOver(),
            'winner' => $game->getWinner(),
        ]);
    }
}

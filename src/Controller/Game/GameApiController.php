<?php

namespace App\Controller\Game;

use App\Game\Game21;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * API-kontroller som returnerar status för spelet 21.
 */
class GameApiController extends AbstractController
{
    /**
     * Returnerar aktuell status för spelet 21 som JSON.
     *
     * Om inget spel finns i sessionen skapas ett nytt.
     * Innehåller information om spelarens och bankens hand, poäng, pengar,
     * aktuell insats samt spelstatus.
     *
     * @param SessionInterface $session Symfony-sessionen.
     * @return JsonResponse JSON-svar med spelets status.
     */
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

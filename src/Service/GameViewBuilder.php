<?php

namespace App\Service;

use App\Service\GameService;

/**
 * Bygger vyn för spelet 21 baserat på aktuellt spelstatus.
 */
class GameViewBuilder
{
    private GameService $gameService;

    /**
     * Konstruktorr av GameService.
     */
    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    /**
     * Skapar en array med data för spelvyn.
     *
     * @return array<string, mixed>
     */
    public function build(): array
    {
        $game = $this->gameService->getGame();
        $reveal = $game->isPlayerStanding() || $game->isGameOver();

        return [
            'playerHand' => $game->getPlayerHand()->getCards(),
            'bankHand' => $reveal ? $game->getBankHand()->getCards() : [],
            'playerValue' => $game->getPlayerValue(),
            'bankValue' => $reveal ? $game->getBankValue() : null,
            'gameOver' => $game->isGameOver(),
            'matchOver' => $game->isMatchOver(),
            'winner' => $game->getWinner(),
            'playerMoney' => $game->getPlayerMoney(),
            'bankMoney' => $game->getBankMoney(),
            'bet' => $game->getBet(),
            'aceChoices' => $game->getAceChoices(),
        ];
    }
}

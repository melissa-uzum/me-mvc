<?php

namespace App\Service;

use App\Game\Game21;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Tjänst för att hantera spelet 21 i sessionen.
 */
class GameService
{
    private const GAME_KEY = 'game21';
    private SessionInterface $session;

    /**
     * Konstruktor som hämtar sessionsinstansen via RequestStack.
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
    }

    /**
     * Hämtar spelet från sessionen, eller skapar ett nytt om det inte finns.
     */
    public function getGame(): Game21
    {
        $game = $this->session->get(self::GAME_KEY);

        if (!$game instanceof Game21) {
            $game = new Game21();
            $this->setGame($game);
        }

        return $game;
    }

    /**
     * Sparar spelet i sessionen.
     */
    public function setGame(Game21 $game): void
    {
        $this->session->set(self::GAME_KEY, $game);
    }

    /**
     * Återställer spelet till ett nytt spel.
     */
    public function resetGame(): void
    {
        $game = new Game21();
        $this->setGame($game);
    }

    /**
     * Startar en ny runda och placerar en insats.
     */
    public function startNewRoundWithBet(int $amount): void
    {
        $game = $this->getGame();
        $game->startNewRound();
        $game->placeBet($amount);
        $this->setGame($game);
    }

    /**
     * Låter spelaren dra ett kort.
     */
    public function playerDraw(): void
    {
        $game = $this->getGame();
        $game->playerDraw();
        $this->setGame($game);
    }

    /**
     * Markerar att spelaren stannar.
     */
    public function playerStands(): void
    {
        $game = $this->getGame();
        $game->playerStands();
        $this->setGame($game);
    }

    /**
     * Sätter ett ess till önskat värde (1 eller 14).
     */
    public function setAceValue(int $index, int $value): void
    {
        $game = $this->getGame();
        $game->setAceValue($index, $value);

        if ($game->getPlayerValue() > 21) {
            $game->playerStands();
            $game->applyResult();
        }

        $this->setGame($game);
    }



    /**
     * Processar spelets gång: låter banken spela och tillämpar resultat vid behov.
     */
    public function processGame(): void
    {
        $game = $this->getGame();

        if ($game->getPlayerValue() > 21) {
            $game->playerStands();
            $game->applyResult();
        } elseif ($game->isPlayerStanding() && !$game->isGameOver()) {
            $game->bankTurn();
            $game->applyResult();
        }

        $this->setGame($game);
    }
}

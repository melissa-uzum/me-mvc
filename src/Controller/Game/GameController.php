<?php

namespace App\Controller\Game;

use App\Service\GameService;
use App\Service\GameViewBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Kontroller för webbspelet 21 (Blackjack-variant).
 */
class GameController extends AbstractController
{
    /**
     * Startvy för spelet.
     */
    #[Route('/game', name: 'game_start')]
    public function start(): Response
    {
        return $this->render('game/index.html.twig');
    }

    /**
     * Dokumentationssida för spelet.
     */
    #[Route('/game/doc', name: 'game_doc')]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }

    /**
     * Huvudvy där spelet spelas.
     *
     * @param GameService $gameService Tjänst för spelhantering.
     * @param GameViewBuilder $gameViewBuilder Bygger vyn med spelets tillstånd.
     */
    #[Route('/game/play', name: 'game_play')]
    public function play(GameService $gameService, GameViewBuilder $gameViewBuilder): Response
    {
        $gameService->processGame();

        return $this->render('game/play.html.twig', $gameViewBuilder->build());
    }

    /**
     * Låter spelaren dra ett kort.
     */
    #[Route('/game/draw', name: 'game_draw', methods: ['POST'])]
    public function draw(GameService $gameService): Response
    {
        $gameService->playerDraw();
        return $this->redirectToRoute('game_play');
    }

    /**
     * Låter spelaren stanna.
     */
    #[Route('/game/stand', name: 'game_stand', methods: ['POST'])]
    public function stand(GameService $gameService): Response
    {
        $gameService->playerStands();
        return $this->redirectToRoute('game_play');
    }

    /**
     * Återställer spelet helt.
     */
    #[Route('/game/reset', name: 'game_reset', methods: ['POST'])]
    public function reset(GameService $gameService): Response
    {
        $gameService->resetGame();
        return $this->redirectToRoute('game_play');
    }

    /**
     * Låter spelaren lägga en insats och startar en ny runda.
     *
     * @param Request $request HTTP-förfrågan med insatsvärde.
     * @param GameService $gameService Tjänst för att hantera spelinstanser.
     */
    #[Route('/game/bet', name: 'game_bet')]
    public function bet(Request $request, GameService $gameService): Response
    {
        $game = $gameService->getGame();

        if ($request->isMethod('POST')) {
            $bet = (int) $request->request->get('bet');

            try {
                $gameService->startNewRoundWithBet($bet);
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

    /**
     * Sätter spelarens valda ess-värde (1 eller 14).
     *
     * @param Request $request POST-data med index och värde.
     * @param GameService $gameService Tjänst som hanterar spelet.
     */
    #[Route('/game/ace', name: 'game_ace', methods: ['POST'])]
    public function ace(Request $request, GameService $gameService): Response
    {
        $index = (int) $request->request->get('index');
        $value = (int) $request->request->get('value');

        if (in_array($value, [1, 14])) {
            $gameService->setAceValue($index, $value);
        }

        return $this->redirectToRoute('game_play');
    }
}

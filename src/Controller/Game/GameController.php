#[Route("/game", name: "game_index")]
public function index(): Response
{
    return $this->render('game/index.html.twig');
}

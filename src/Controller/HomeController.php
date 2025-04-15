<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'name' => 'Melissa',
            'image' => 'images/me.jpeg',
            'text1' => 'Jag gillar att koda, spela spel som Elden Ring, Valorant och jag gillar katter!',
            'text2' => 'Jag är även intresserad av att bygga mekaniska tangentbord och med tanke på att jag spillde varm mjölk med honung på mitt favorit tangentbord beställde jag lite delar och bygger ett nytt när de kommer! Tragiskt. Eller?...',
        ]);
    }
}

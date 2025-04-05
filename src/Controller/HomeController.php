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
            'text1' => 'Jag gillar att koda, spela spel som Elden Ring, Valorant och Sons Of The Forest och jag gillar katter! Just nu har jag faktiskt börjat spela Indiana Jones spelet, riktig nostalgi fårn när jag var yngre och kollade på alla filmerna!',
            'text2' => 'Jag är även intresserad av att bygga mekaniska tangentbord och tänkte unna mig med ett nytt fint tangentbord när denna termin är över! Efter nästa termin uppgraderar jag kanske min PC så den klarar av lite fler spel!',
        ]);
    }
}

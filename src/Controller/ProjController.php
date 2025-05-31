<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Kontroller som hanterar vyer kopplade till projektets introduktion och information.
 */
class ProjController extends AbstractController
{
    /**
     * Visar projektets huvudsida.
     *
     * @return Response
     */
    #[Route('/proj', name: 'proj_index')]
    public function index(): Response
    {
        return $this->render('proj/index.html.twig');
    }

    /**
     * Visar sidan "Om projektet" med beskrivning av syfte och innehåll.
     *
     * @return Response
     */
    #[Route('/proj/about', name: 'proj_about')]
    public function about(): Response
    {
        return $this->render('proj/about.html.twig');
    }

    /**
     * Visar information om databasens struktur och tabeller.
     *
     * @return Response
     */
    #[Route('/proj/about/database', name: 'proj_about_database')]
    public function aboutDatabase(): Response
    {
        return $this->render('proj/about_database.html.twig');
    }

    /**
     * Visar beskrivning av tekniska utmaningar och lösningar i projektet.
     *
     * @return Response
     */
    #[Route('/proj/about/features', name: 'proj_about_features')]
    public function features(): Response
    {
        return $this->render('proj/features.html.twig');
    }
}

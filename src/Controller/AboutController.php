<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    #[Route('/about', name: 'about')]
    public function index(): Response
    {
        return $this->render('about/index.html.twig', [
            'image' => 'images/php.png',
            'course_link' => 'https://github.com/dbwebb-se/mvc',
            'my_repo' => 'https://github.com/melissa-uzum/me-mvc',
        ]);
    }
}

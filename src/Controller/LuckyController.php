<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyController extends AbstractController
{
    #[Route('/lucky', name: 'lucky')]
    public function index(): Response
    {
        $number = random_int(1, 100);
        $images = [
            'images/cat1.jpg',
            'images/cat2.jpg',
            'images/cat3.jpg',
            'images/cat4.jpg',
            'images/cat5.jpg',
            'images/cat6.jpg',
            'images/cat7.jpg',
            'images/cat8.jpg',
            'images/cat9.jpg',
            'images/cat10.jpg',
            'images/cat11.jpg',
        ];

        $randomImage = $images[array_rand($images)];

        return $this->render('lucky/index.html.twig', [
            'number' => $number,
            'image' => $randomImage,
        ]);
    }
}

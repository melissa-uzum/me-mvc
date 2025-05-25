<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatsController extends AbstractController
{
    #[Route('/proj/stats', name: 'proj_stats')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $connection = $doctrine->getConnection('sustainability');

        $co2_sweden = $connection->fetchAllAssociative(
            'SELECT year, value FROM measurement WHERE indicator_id = 1 ORDER BY year'
        );
        $co2_denmark = $connection->fetchAllAssociative(
            'SELECT year, value FROM measurement WHERE indicator_id = 3 ORDER BY year'
        );

        $water_sweden = $connection->fetchAllAssociative(
            'SELECT year, value FROM measurement WHERE indicator_id = 2 ORDER BY year'
        );
        $water_denmark = $connection->fetchAllAssociative(
            'SELECT year, value FROM measurement WHERE indicator_id = 4 ORDER BY year'
        );

        $years = array_column($co2_sweden, 'year');

        return $this->render('stats/index.html.twig', [
            'years' => json_encode($years),
            'co2_sweden' => json_encode(array_column($co2_sweden, 'value')),
            'co2_denmark' => json_encode(array_column($co2_denmark, 'value')),
            'water_sweden' => json_encode(array_column($water_sweden, 'value')),
            'water_denmark' => json_encode(array_column($water_denmark, 'value')),
        ]);
    }
}

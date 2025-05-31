<?php

namespace App\Controller;

use App\Sustainability\Entity\Indicator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatsController extends AbstractController
{
    /**
     * Visar statistik över växthusgasutsläpp och tillgång till rent vatten
     * för Sverige och Danmark under åren 2015–2020.
     */
    #[Route('/proj/stats', name: 'proj_stats')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager('sustainability');

        $co2Sweden = $em->getRepository(Indicator::class)->find(1);
        $waterSweden = $em->getRepository(Indicator::class)->find(2);
        $co2Denmark = $em->getRepository(Indicator::class)->find(3);
        $waterDenmark = $em->getRepository(Indicator::class)->find(4);

        $extractData = function ($indicator) {
            $measurements = $indicator->getMeasurements()->toArray();

            usort($measurements, fn ($a, $b) => $a->getYear() <=> $b->getYear());

            $years = array_map(fn ($m) => $m->getYear(), $measurements);
            $values = array_map(fn ($m) => $m->getValue(), $measurements);

            return [$years, $values];
        };

        [$years, $co2SwedenData] = $extractData($co2Sweden);
        [, $co2DenmarkData] = $extractData($co2Denmark);
        [, $waterSwedenData] = $extractData($waterSweden);
        [, $waterDenmarkData] = $extractData($waterDenmark);

        return $this->render('stats/index.html.twig', [
            'years' => json_encode($years),
            'co2_sweden' => json_encode($co2SwedenData),
            'co2_denmark' => json_encode($co2DenmarkData),
            'water_sweden' => json_encode($waterSwedenData),
            'water_denmark' => json_encode($waterDenmarkData),
        ]);
    }
}

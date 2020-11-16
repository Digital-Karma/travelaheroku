<?php

namespace App\Controller;

use App\Service\Stats;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(EntityManagerInterface $manager, Stats $statsService)
    {
        $stats = $statsService->getStats();

        $bestFocusPays = $statsService->getFocusPaysStats('DESC');
        $badFocusPays = $statsService->getFocusPaysStats('ASC');

        $bestFocusVille = $statsService->getFocusVilleStats('DESC');
        $badFocusVille = $statsService->getFocusVilleStats('ASC');

        $bestFocusLieu = $statsService->getFocusLieuStats('DESC');
        $badFocusLieu = $statsService->getFocusLieuStats('ASC');


        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats,
            'bestFocusPays' => $bestFocusPays,
            'badFocusPays' => $badFocusPays,
            'bestFocusVille' => $bestFocusVille,
            'badFocusVille' => $badFocusVille,
            'bestFocusLieu' => $bestFocusLieu,
            'badFocusLieu' => $badFocusLieu
        ]);
    }
}

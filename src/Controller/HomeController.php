<?php

namespace App\Controller;

use App\Repository\FocusLieuRepository;
use App\Repository\FocusPaysRepository;
use App\Repository\FocusVilleRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(FocusPaysRepository $focusPays, FocusVilleRepository $focusVille, FocusLieuRepository $focusLieu)
    {
        return $this->render(
            'home.html.twig', [
            'title' => 'Accueil',
            'fpays' => $focusPays->findBestPays(3),
            'fville' => $focusVille->findBestVille(3),
            'flieu' => $focusLieu->findBestLieu(3)
        ]);
    }
}

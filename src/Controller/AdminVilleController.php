<?php

namespace App\Controller;

use App\Entity\FocusVille;
use App\Service\Pagination;
use App\Form\FocusVilleType;
use App\Repository\FocusVilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminVilleController extends AbstractController
{
    /**
     * @Route("/admin/ville/{page}", name="admin_ville_index", requirements={"page": "\d+"})
     */
    public function index(FocusVilleRepository $repo, $page = 1, Pagination $pagination)
    {
        $pagination->setEntityClass(FocusVille::class)
                   ->setPage($page);

        return $this->render('admin/focus_ville/index.html.twig', [
            'pagination' => $pagination
        ]);
    }


    /**
     * Permet d'afficher le formulaire d'édition
     *
     * @Route("/admin/ville/{id}/edit", name="admin_focusville_edit")
     * 
     * @param FocusVille $focusville
     * @return Response
     */
    public function edit(FocusVille $focusville, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(FocusVilleType::class, $focusville);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($focusville);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$focusville->getTitle()} a bien été enregistrée !"
            );
        }

        return $this->render('admin/focus_ville/edit.html.twig', [
            'focusville' => $focusville,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une annonce
     *
     * @Route("/admin/ville/{id}/delete", name="admin_focusville_delete")
     * 
     * @param FocusVille $focusville
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(FocusVille $focusville, EntityManagerInterface $manager) {
        
        $manager->remove($focusville);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'annonce <strong>{$focusville->getTitle()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute('admin_ville_index');
    }
}

<?php

namespace App\Controller;

use App\Entity\FocusLieu;
use App\Form\FocusLieuType;
use App\Service\Pagination;
use App\Repository\FocusLieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminLieuController extends AbstractController
{
    /**
     * @Route("/admin/lieu/{page}", name="admin_lieu_index", requirements={"page": "\d+"})
     */
    public function index(FocusLieuRepository $repo, $page = 1, Pagination $pagination)
    {
        $pagination->setEntityClass(FocusLieu::class)
                   ->setPage($page);

        return $this->render('admin/focus_lieu/index.html.twig', [
            'pagination' => $pagination
        ]);
    }
    
    /**
     * Permet d'afficher le formulaire d'édition
     *
     * @Route("/admin/lieu/{id}/edit", name="admin_focuslieu_edit")
     * 
     * @param FocusLieu $focuslieu
     * @return Response
     */
    public function edit(FocusLieu $focuslieu, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(FocusLieuType::class, $focuslieu);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($focuslieu);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$focuslieu->getTitle()} a bien été enregistrée !"
            );
        }

        return $this->render('admin/focus_lieu/edit.html.twig', [
            'focuslieu' => $focuslieu,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une annonce
     *
     * @Route("/admin/lieu/{id}/delete", name="admin_focuslieu_delete")
     * 
     * @param FocusLieu $focuslieu
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(FocusLieu $focuslieu, EntityManagerInterface $manager) {
        
        $manager->remove($focuslieu);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'annonce <strong>{$focuslieu->getTitle()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute('admin_lieu_index');
    }
}

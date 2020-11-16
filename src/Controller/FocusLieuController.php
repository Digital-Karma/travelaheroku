<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\FocusLieu;
use App\Form\CommentType;
use App\Form\FocusLieuType;
use App\Repository\FocusLieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FocusLieuController extends AbstractController
{
    /**
     * @Route("/focus_lieu", name="focus_lieu")
     */
    public function index(FocusLieuRepository $repo)
    {
        $focus= $repo->findAll();

        return $this->render('focus_lieu/index.html.twig', [
            'focus' => $focus
        ]);
    }
    
    /**
     * Permet de créer un Focus Lieu
     *
     * @Route("/ads/new_lieu", name="ads_lieu")
     * @IsGranted("ROLE_USER")
     * 
    * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager) {

        $focus_lieu = new FocusLieu();

        $form = $this->createForm(FocusLieuType::class, $focus_lieu);

        $form->handleRequest($request);

        //On fait appel au manager avec EntityMangerInterface pour pouvoir l'utiliser
        //Rappel le manager c'est lui qui gere l'enregistrement des données en BDD

        //Avec ce If on verfifie si le form est soumit et valid avant de le faire persister en BDD
        if($form->isSubmitted() && $form->isValid()){

            $focus_lieu->setAuthor($this->getUser());

            $manager->persist($focus_lieu);
            $manager->flush();

            return $this->redirectToRoute('focus_show_lieu', [
                'slug' => $focus_lieu->getSlug()
            ]);
        }


        return $this->render('focus_lieu/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'éditer un focus lieu
     * 
     * @Route("/focus_lieu/{slug}/edit", name="lieu_edit")
     * @Security("is_granted('ROLE_USER') and user === focus_lieu.getAuthor() or is_granted('ROLE_ADMIN')", message="Vous ne pouvez pas modifier un focus lieu que vous n'avez pas créer")
     * 
     * @return Response
     */
    public function edit(FocusLieu $focus_lieu, Request $request, EntityManagerInterface $manager) {

        $form = $this->createForm(FocusLieuType::class, $focus_lieu);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($focus_lieu);
            $manager->flush();

            return $this->redirectToRoute('focus_show_lieu', [
                'slug' => $focus_lieu->getSlug()
            ]);
        }

        return $this->render('focus_lieu/edit.html.twig', [
            'form' => $form->createView(),
            'focus_lieu' => $focus_lieu
        ]);
    }

    /**
     * Permet d'afficher une seul annonce
     *
     * @Route("/focus_lieu/{slug}", name="focus_show_lieu")
     * 
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function show(FocusLieu $focus, Request $request, EntityManagerInterface $manager){

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setFocusLieu($focus)
                    ->setAuthor($this->getUser());

                    $manager->persist($comment);
                    $manager->flush();

                    $this->addFlash(
                        'success',
                        "Votre commentaire à bien été prix en compte"
                    );
        }
            return $this->render('focus_lieu/show.html.twig', [
            'focus' => $focus,
            'form'  => $form->createView()
        ]);
    }
    
    /**
     * Permet de supprimer un focus
     * 
     * @Route("/focus_lieu/{slug}/delete", name="lieu_delete")
     * @Security("is_granted('ROLE_USER') and user === focus.getAuthor()", message="Vous ne pouvez supprimer un focus que vous n'avez pas créer.")
     *
     * @param FocusLieu $focus
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(FocusLieu $focus, EntityManagerInterface $manager){
        $manager->remove($focus);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le focus <strong>{$focus->getTitle()}</strong> a bien été supprimé !"
        );

        return $this->redirectToRoute("focus_lieu");
    }
}

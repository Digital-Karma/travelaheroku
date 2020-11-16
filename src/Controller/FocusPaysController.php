<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\FocusPays;
use App\Form\CommentType;
use App\Entity\FocusVille;
use App\Form\FocusPaysType;
use App\Repository\FocusPaysRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FocusPaysController extends AbstractController
{
    /**
     * @Route("/focus_pays", name="focus_pays")
     */
    public function index(FocusPaysRepository $repo)
    {
        $focus= $repo->findAll();

        return $this->render('focus_pays/index.html.twig', [
            'focus' => $focus
        ]);
    }
    
    /**
     * Permet de créer un Focus Pays
     *
     * @Route("/ads/new_pays", name="ads_pays")
     * @IsGranted("ROLE_USER")
     * 
    * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager) {

        $focus_pays = new FocusPays();

        $form = $this->createForm(FocusPaysType::class, $focus_pays);

        $form->handleRequest($request);

        //On fait appel au manager avec EntityMangerInterface pour pouvoir l'utiliser
        //Rappel le manager c'est lui qui gere l'enregistrement des données en BDD

        //Avec ce If on verfifie si le form est soumit et valid avant de le faire persister en BDD
        if($form->isSubmitted() && $form->isValid()){

            $focus_pays->setAuthor($this->getUser());

            $manager->persist($focus_pays);
            $manager->flush();

            return $this->redirectToRoute('focus_show_pays', [
                'slug' => $focus_pays->getSlug()
            ]);
        }

        return $this->render('focus_pays/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'éditer une annonce
     * 
     * @Route("/focus_pays/{slug}/edit", name="pays_edit")
     * @Security("is_granted('ROLE_USER') and user === focus_pays.getAuthor() or is_granted('ROLE_ADMIN')", message="Vous ne pouvez pas modifier un focus pays que vous n'avez pas créer")
     * 
     * @return Response
     */
    public function edit(FocusPays $focus_pays, Request $request, EntityManagerInterface $manager) {

        $form = $this->createForm(FocusPaysType::class, $focus_pays);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($focus_pays);
            $manager->flush();

            return $this->redirectToRoute('focus_show_pays', [
                'slug' => $focus_pays->getSlug()
            ]);
        }

        return $this->render('focus_pays/edit.html.twig', [
            'form' => $form->createView(),
            'focus_pays' => $focus_pays
        ]);
    }

    /**
     * Permet d'afficher un focus pays
     *
     * @Route("/focus_pays/{slug}", name="focus_show_pays")
     * 
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function show(FocusPays $focus, Request $request, EntityManagerInterface $manager){

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setFocusPays($focus)
                    ->setAuthor($this->getUser());

                    $manager->persist($comment);
                    $manager->flush();

                    $this->addFlash(
                        'success',
                        "Votre commentaire à bien été prix en compte"
                    );
        }

        return $this->render('focus_pays/show.html.twig', [
            'focus' => $focus,
            'form'  => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un focus
     * 
     * @Route("/focus_pays/{slug}/delete", name="pays_delete")
     * @Security("is_granted('ROLE_USER') and user === focus.getAuthor()", message="Vous ne pouvez supprimer un focus que vous n'avez pas créer.")
     *
     * @param FocusPays $focus
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(FocusPays $focus, EntityManagerInterface $manager){
        $manager->remove($focus);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le focus <strong>{$focus->getTitle()}</strong> a bien été supprimé !"
        );

        return $this->redirectToRoute("focus_pays");
    }
}

<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Entity\FocusVille;
use App\Form\FocusVilleType;
use App\Repository\FocusVilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FocusVilleController extends AbstractController
{
    /**
     * @Route("/focus_ville", name="focus_ville")
     */
    public function index(FocusVilleRepository $repo)
    {
        $focus= $repo->findAll();

        return $this->render('focus_ville/index.html.twig', [
            'focus' => $focus
        ]);
    }
    
    /**
     * Permet de créer un Focus Ville
     *
     * @Route("/ads/new_ville", name="ads_ville")
     * @IsGranted("ROLE_USER")
     * 
    * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager) {

        $focus_ville = new FocusVille();

        $form = $this->createForm(FocusVilleType::class, $focus_ville);
        $form->handleRequest($request);

        //On fait appel au manager avec EntityMangerInterface pour pouvoir l'utiliser
        //Rappel le manager c'est lui qui gere l'enregistrement des données en BDD

        //Avec ce If on verfifie si le form est soumit et valid avant de le faire persister en BDD
        if($form->isSubmitted() && $form->isValid()){

            $focus_ville->setAuthor($this->getUser());


            $manager->persist($focus_ville);
            $manager->flush();
            
            return $this->redirectToRoute('focus_show_ville', [
                'slug' => $focus_ville->getSlug()
            ]);
        }


        return $this->render('focus_ville/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * Permet d'éditer un focus ville
     * 
     * @Route("/focus_ville/{slug}/edit", name="ville_edit")
     * @Security("is_granted('ROLE_USER') and user === focus_ville.getAuthor() or is_granted('ROLE_ADMIN')", message="Vous ne pouvez pas modifier un focus ville que vous n'avez pas créer")
     * 
     * @return Response
     */
    public function edit(FocusVille $focus_ville, Request $request, EntityManagerInterface $manager) {

        $form = $this->createForm(FocusVilleType::class, $focus_ville);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($focus_ville);
            $manager->flush();
            
            return $this->redirectToRoute('focus_show_ville', [
                'slug' => $focus_ville->getSlug()
            ]);
        }

        return $this->render('focus_ville/edit.html.twig', [
            'form' => $form->createView(),
            'focus_ville' => $focus_ville
        ]);
    }


    /**
     * Permet d'afficher une seul annonce
     *
     * @Route("/focus_ville/{slug}", name="focus_show_ville")
     * 
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function show(FocusVille $focus, Request $request, EntityManagerInterface $manager){

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setFocusVille($focus)
                    ->setAuthor($this->getUser());

                    $manager->persist($comment);
                    $manager->flush();

                    $this->addFlash(
                        'success',
                        "Votre commentaire à bien été prix en compte"
                    );
        }
        return $this->render('focus_ville/show.html.twig', [
            'focus' => $focus,
            'form'  => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un focus
     * 
     * @Route("/focus_ville/{slug}/delete", name="ville_delete")
     * @Security("is_granted('ROLE_USER') and user === focus.getAuthor()", message="Vous ne pouvez supprimer un focus que vous n'avez pas créer.")
     *
     * @param FocusVille $focus
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(FocusVille $focus, EntityManagerInterface $manager){
        $manager->remove($focus);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le focus <strong>{$focus->getTitle()}</strong> a bien été supprimé !"
        );

        return $this->redirectToRoute("focus_ville");
    }
}

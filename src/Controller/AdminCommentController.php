<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Service\Pagination;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comment/{page}", name="admin_comment_index", requirements={"page": "\d+"})
     */
    public function index(CommentRepository $repo, $page = 1, Pagination $pagination)
    {
        $pagination->setEntityClass(Comment::class)
                   ->setPage($page);

        return $this->render('admin/comment/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition des commentaires
     * 
     * @Route("/admin/comment/{id}/edit", name="admin_comment_edit")
     *
     * @param Comment $comment
     * @return Response
     */
    public function edit(Comment $comment, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$comment->getId()}</strong> a bien été enregistré"
            );
        }

        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un commentaire
     * 
     * @Route("/admin/comment/{id}/delete", name="admin_comment_delete")
     *
     * @param Comment $comment
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Comment $comment, EntityManagerInterface $manager){
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'annonce <strong>{$comment->getId()} a bien été supprimée !"
        );

        return $this->redirectToRoute('admin_comment_index');
    }

}

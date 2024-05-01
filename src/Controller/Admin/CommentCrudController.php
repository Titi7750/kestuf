<?php

namespace App\Controller\Admin;

use App\Entity\CommentEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class CommentCrudController extends DashboardController
{

    #[Route('/admin/comment/publish/{id}', name: 'admin_comment_publish')]
    public function publishComment(CommentEvent $comment, EntityManagerInterface $entityManagerInterface)
    {
        $comment->setActive(true);
        $entityManagerInterface->flush();

        return $this->redirectToRoute('admin_comment');
    }

    #[Route('/admin/comment/unpublish/{id}', name: 'admin_comment_unpublish')]
    public function unpublishComment(CommentEvent $comment, EntityManagerInterface $entityManagerInterface)
    {
        $comment->setActive(false);
        $entityManagerInterface->flush();

        return $this->redirectToRoute('admin_comment');
    }

    #[Route('/admin/comment', name: 'admin_comment')]
    public function listComment(EntityManagerInterface $entityManagerInterface)
    {
        $comments = $entityManagerInterface->getRepository(CommentEvent::class)->findAll();

        return $this->render('admin/comment/comment.html.twig', [
            'comments' => $comments
        ]);
    }

    #[Route('/admin/comment/show/{id}', name: 'admin_comment_show')]
    public function showComment($id, EntityManagerInterface $entityManagerInterface)
    {
        $comment = $entityManagerInterface->getRepository(CommentEvent::class)->find($id);

        if (!$comment) {
            throw $this->createNotFoundException('Le commentaire n\'existe pas');
        }

        return $this->render('admin/comment/showComment.html.twig', [
            'comment' => $comment
        ]);
    }

    #[Route('/admin/comment/delete/{id}', name: 'admin_comment_delete')]
    public function deleteComment($id, EntityManagerInterface $entityManagerInterface)
    {
        $comment = $entityManagerInterface->getRepository(CommentEvent::class)->find($id);

        if (!$comment) {
            throw $this->createNotFoundException('Le commentaire n\'existe pas');
        }

        $entityManagerInterface->remove($comment);
        $entityManagerInterface->flush();

        $this->addFlash('success', 'Le commentaire a bien été supprimé');
        return $this->redirectToRoute('admin_comment');
    }
}

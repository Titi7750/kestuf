<?php

namespace App\Controller\Admin;

use App\Entity\CommentEvent;
use App\Entity\CommentUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class CommentCrudController extends DashboardController
{
    // Commentaire Event
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

    // Commentaire User
    #[Route('/admin/commentuser/publish/{id}', name: 'admin_commentUser_publish')]
    public function publishCommentUser(CommentUser $commentUser, EntityManagerInterface $entityManagerInterface)
    {
        $commentUser->setActive(true);
        $entityManagerInterface->flush();

        return $this->redirectToRoute('admin_commentUser');
    }

    #[Route('/admin/commentuser/unpublish/{id}', name: 'admin_commentUser_unpublish')]
    public function unpublishCommentUser(CommentUser $commentUser, EntityManagerInterface $entityManagerInterface)
    {
        $commentUser->setActive(false);
        $entityManagerInterface->flush();

        return $this->redirectToRoute('admin_commentUser');
    }

    #[Route('/admin/commentuser', name: 'admin_commentUser')]
    public function listCommentUser(EntityManagerInterface $entityManagerInterface)
    {
        $commentsUser = $entityManagerInterface->getRepository(CommentUser::class)->findAll();

        return $this->render('admin/comment/commentUser.html.twig', [
            'commentsUser' => $commentsUser
        ]);
    }

    #[Route('/admin/commentuser/show/{id}', name: 'admin_commentUser_show')]
    public function showCommentUser($id, EntityManagerInterface $entityManagerInterface)
    {
        $commentUser = $entityManagerInterface->getRepository(CommentUser::class)->find($id);

        if (!$commentUser) {
            throw $this->createNotFoundException('Le commentaire n\'existe pas');
        }

        return $this->render('admin/comment/showCommentUser.html.twig', [
            'commentUser' => $commentUser
        ]);
    }

    #[Route('/admin/commentuser/delete/{id}', name: 'admin_commentUser_delete')]
    public function deleteCommentUser($id, EntityManagerInterface $entityManagerInterface)
    {
        $commentUser = $entityManagerInterface->getRepository(CommentUser::class)->find($id);

        if (!$commentUser) {
            throw $this->createNotFoundException('Le commentaire n\'existe pas');
        }

        $entityManagerInterface->remove($commentUser);
        $entityManagerInterface->flush();

        $this->addFlash('success', 'Le commentaire a bien été supprimé');
        return $this->redirectToRoute('admin_commentUser');
    }
}

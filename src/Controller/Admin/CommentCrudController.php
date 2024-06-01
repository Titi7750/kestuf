<?php

namespace App\Controller\Admin;

use App\Entity\CommentEvent;
use App\Entity\CommentUser;
use App\Services\Admin\AdminCommentService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class CommentCrudController extends DashboardController
{
    private $adminCommentService;

    public function __construct(AdminCommentService $adminCommentService)
    {
        $this->adminCommentService = $adminCommentService;
    }

    // Commentaire Event
    /**
     * Publish a comment
     *
     * @param CommentEvent $comment
     * @return void
     */
    #[Route('/admin/comment/publish/{id}', name: 'admin_comment_publish')]
    public function publishComment(CommentEvent $comment)
    {
        $this->adminCommentService->publishComment($comment);
        return $this->redirectToRoute('admin_comment');
    }

    /**
     * Unpublish a comment
     *
     * @param CommentEvent $comment
     * @return void
     */
    #[Route('/admin/comment/unpublish/{id}', name: 'admin_comment_unpublish')]
    public function unpublishComment(CommentEvent $comment)
    {
        $this->adminCommentService->unpublishComment($comment);
        return $this->redirectToRoute('admin_comment');
    }

    /**
     * List all comments
     *
     * @return void
     */
    #[Route('/admin/comment', name: 'admin_comment')]
    public function listComment()
    {
        $comments = $this->adminCommentService->listComments();

        return $this->render('admin/comment/comment.html.twig', [
            'comments' => $comments
        ]);
    }

    /**
     * Get a comment by its id
     *
     * @param integer $id
     * @return CommentEvent|null
     */
    #[Route('/admin/comment/show/{id}', name: 'admin_comment_show')]
    public function showComment(int $id)
    {
        $comment = $this->adminCommentService->getCommentById($id);

        if (!$comment) {
            throw $this->createNotFoundException('Le commentaire n\'existe pas');
        }

        return $this->render('admin/comment/showComment.html.twig', [
            'comment' => $comment
        ]);
    }

    /**
     * Delete a comment
     *
     * @param CommentEvent $comment
     * @return void
     */
    #[Route('/admin/comment/delete/{id}', name: 'admin_comment_delete')]
    public function deleteComment(int $id)
    {
        $comment = $this->adminCommentService->getCommentById($id);

        if (!$comment) {
            throw $this->createNotFoundException('Le commentaire n\'existe pas');
        }

        $this->adminCommentService->deleteComment($comment);

        $this->addFlash('success', 'Le commentaire a bien été supprimé');
        return $this->redirectToRoute('admin_comment');
    }

    // Commentaire User
    /**
     * Publish a comment
     * 
     * @param CommentUser $commentUser
     * @return void
     */
    #[Route('/admin/commentuser/publish/{id}', name: 'admin_commentUser_publish')]
    public function publishCommentUser(CommentUser $commentUser)
    {
        $this->adminCommentService->publishCommentUser($commentUser);
        return $this->redirectToRoute('admin_commentUser');
    }

    /**
     * Unpublish a comment
     *
     * @param CommentUser $commentUser
     * @return void
     */
    #[Route('/admin/commentuser/unpublish/{id}', name: 'admin_commentUser_unpublish')]
    public function unpublishCommentUser(CommentUser $commentUser)
    {
        $this->adminCommentService->unpublishCommentUser($commentUser);
        return $this->redirectToRoute('admin_commentUser');
    }

    /**
     * List all comments
     *
     * @return void
     */
    #[Route('/admin/commentuser', name: 'admin_commentUser')]
    public function listCommentUser()
    {
        $commentsUser = $this->adminCommentService->listCommentsUser();

        return $this->render('admin/comment/commentUser.html.twig', [
            'commentsUser' => $commentsUser
        ]);
    }

    /**
     * Get a comment by its id
     *
     * @param integer $id
     * @return CommentUser|null
     */
    #[Route('/admin/commentuser/show/{id}', name: 'admin_commentUser_show')]
    public function showCommentUser(int $id)
    {
        $commentUser = $this->adminCommentService->getCommentUserById($id);

        if (!$commentUser) {
            throw $this->createNotFoundException('Le commentaire n\'existe pas');
        }

        return $this->render('admin/comment/showCommentUser.html.twig', [
            'commentUser' => $commentUser
        ]);
    }

    /**
     * Delete a comment
     *
     * @param CommentUser $commentUser
     * @return void
     */
    #[Route('/admin/commentuser/delete/{id}', name: 'admin_commentUser_delete')]
    public function deleteCommentUser(int $id)
    {
        $commentUser = $this->adminCommentService->getCommentUserById($id);

        if (!$commentUser) {
            throw $this->createNotFoundException('Le commentaire n\'existe pas');
        }

        $this->adminCommentService->deleteCommentUser($commentUser);

        $this->addFlash('success', 'Le commentaire a bien été supprimé');
        return $this->redirectToRoute('admin_commentUser');
    }
}

<?php

namespace App\Services\Admin;

use App\Entity\CommentEvent;
use App\Entity\CommentUser;
use Doctrine\ORM\EntityManagerInterface;

class AdminCommentService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Commentaire Event
    /**
     * Publish a comment
     *
     * @param CommentEvent $comment
     * @return void
     */
    public function publishComment(CommentEvent $comment)
    {
        $comment->setActive(true);
        $this->entityManager->flush();
    }

    /**
     * Unpublish a comment
     *
     * @param CommentEvent $comment
     * @return void
     */
    public function unpublishComment(CommentEvent $comment)
    {
        $comment->setActive(false);
        $this->entityManager->flush();
    }

    /**
     * List all comments
     *
     * @return void
     */
    public function listComments()
    {
        return $this->entityManager->getRepository(CommentEvent::class)->findAll();
    }

    /**
     * Get a comment by its id
     *
     * @param integer $id
     * @return CommentEvent|null
     */
    public function getCommentById(int $id): ?CommentEvent
    {
        return $this->entityManager->getRepository(CommentEvent::class)->find($id);
    }

    /**
     * Delete a comment
     *
     * @param CommentEvent $comment
     * @return void
     */
    public function deleteComment(CommentEvent $comment)
    {
        $this->entityManager->remove($comment);
        $this->entityManager->flush();
    }

    // Commentaire User
    /**
     * Publish a comment
     *
     * @param CommentUser $commentUser
     * @return void
     */
    public function publishCommentUser(CommentUser $commentUser)
    {
        $commentUser->setActive(true);
        $this->entityManager->flush();
    }

    /**
     * Unpublish a comment
     *
     * @param CommentUser $commentUser
     * @return void
     */
    public function unpublishCommentUser(CommentUser $commentUser)
    {
        $commentUser->setActive(false);
        $this->entityManager->flush();
    }

    /**
     * List all comments
     *
     * @return void
     */
    public function listCommentsUser()
    {
        return $this->entityManager->getRepository(CommentUser::class)->findAll();
    }

    /**
     * Get a comment by its id
     *
     * @param integer $id
     * @return CommentUser|null
     */
    public function getCommentUserById(int $id): ?CommentUser
    {
        return $this->entityManager->getRepository(CommentUser::class)->find($id);
    }

    /**
     * Delete a comment
     *
     * @param CommentUser $commentUser
     * @return void
     */
    public function deleteCommentUser(CommentUser $commentUser)
    {
        $this->entityManager->remove($commentUser);
        $this->entityManager->flush();
    }
}

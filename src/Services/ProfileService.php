<?php

namespace App\Services;

use App\Entity\CommentUser;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProfileService
{
    private $userRepository;
    private $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Get user by surname
     *
     * @param string $surname
     * @return User|null
     */
    public function getUserBysurname(string $surname): ?User
    {
        return $this->userRepository->findOneBy(['surname' => $surname]);
    }

    /**
     * Add comment to user
     *
     * @param User $user
     * @param User $currentUser
     * @param CommentUser $comment
     * @return void
     */
    public function addComment(User $user, User $currentUser, CommentUser $comment): void
    {
        $comment->setUserSendComment($currentUser);
        $comment->setUserReceiveComment($user);
        $comment->setCreatedAt(new \DateTimeImmutable());
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }

    /**
     * Update user
     *
     * @param User $user
     * @return void
     */
    public function updateUser(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}

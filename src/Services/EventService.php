<?php

namespace App\Services;

use App\Entity\CommentEvent;
use App\Entity\Event;
use App\Entity\User;
use App\Model\SearchData;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;

class EventService
{
    private $eventRepository;
    private $entityManager;

    public function __construct(EventRepository $eventRepository, EntityManagerInterface $entityManager)
    {
        $this->eventRepository = $eventRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Get filtered events
     *
     * @param SearchData $searchData
     * @return Event[]
     */
    public function getFilteredEvents(SearchData $searchData)
    {
        return $this->eventRepository->findSearch($searchData);
    }

    /**
     * Get event by id
     *
     * @param int $id
     * @return Event|null
     */
    public function getEventById(int $id): ?Event
    {
        return $this->eventRepository->find($id);
    }

    /**
     * Add comment to event
     *
     * @param Event $event
     * @param User $user
     * @param CommentEvent $comment
     * @return void
     */
    public function addComment(Event $event, User $user, CommentEvent $comment): void
    {
        $comment->setUserCommentEvent($user);
        $comment->setEventCommentEvent($event);
        $comment->setCreatedAt(new \DateTimeImmutable());
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }

    /**
     * Toggle favorite event for user
     *
     * @param User $user
     * @param Event $event
     * @return void
     */
    public function toggleFavorite(User $user, Event $event): void
    {
        if ($user->getEventUserFavorite()->contains($event)) {
            $user->removeEventUserFavorite($event);
        } else {
            $user->addEventUserFavorite($event);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Toggle participation event for user
     *
     * @param User $user
     * @param Event $event
     * @return void
     */
    public function toggleParticipation(User $user, Event $event): void
    {
        if ($user->getEventUserParticipant()->contains($event)) {
            $user->removeEventUserParticipant($event);
        } else {
            $user->addEventUserParticipant($event);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}

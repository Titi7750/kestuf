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
     * Get data for map
     * 
     * @param EventRepository $eventRepository
     */

    public function getMapData()
    {
        $filterData = new SearchData();
        $events = $this->eventRepository->findSearch($filterData);

        $data = [];
        foreach ($events as $event) {
            $data[] = [
                'id' => $event->getId(),
                'name' => $event->getName(),
                'address' => $event->getAddress(),
                'latitude' => $event->getLatitude(),
                'longitude' => $event->getLongitude(),
            ];
        }

        return $data;
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

    /**
     * Toggle outlet event for user
     * 
     * @param User $user
     * @param Event $event
     * @return void
     */
    public function toggleOutlet(User $user, Event $event): void
    {
        if ($user->getEventUserOutlet()->contains($event)) {
            $user->removeEventUserOutlet($event);
        } else {
            $user->addEventUserOutlet($event);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}

<?php

namespace App\Services\Admin;

use App\Entity\Event;
use App\Form\EventType;
use App\Services\FileUploader;
use App\Services\Localisator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class AdminEventService
{
    private $entityManager;
    private $fileUploader;
    private $localisator;
    private $formFactory;

    public function __construct(EntityManagerInterface $entityManager, FileUploader $fileUploader, Localisator $localisator, FormFactoryInterface $formFactory)
    {
        $this->entityManager = $entityManager;
        $this->fileUploader = $fileUploader;
        $this->localisator = $localisator;
        $this->formFactory = $formFactory;
    }

    /**
     * List all events
     *
     * @return void
     */
    public function listEvents()
    {
        return $this->entityManager->getRepository(Event::class)->findAll();
    }

    /**
     * Get event by id
     *
     * @param integer $id
     * @return Event|null
     */
    public function getEventById(int $id): ?Event
    {
        return $this->entityManager->getRepository(Event::class)->find($id);
    }

    /**
     * Create event
     *
     * @param Request $request
     * @return array
     */
    public function createEvent(Request $request): array
    {
        $event = new Event();
        $form = $this->formFactory->create(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleEventForm($event, $form);
            $this->entityManager->persist($event);
            $this->entityManager->flush();

            return ['success' => true, 'event' => $event];
        }

        return ['success' => false, 'form' => $form];
    }

    /**
     * Update event
     *
     * @param integer $id
     * @param Request $request
     * @return array
     */
    public function updateEvent(int $id, Request $request): array
    {
        $event = $this->getEventById($id);

        if (!$event) {
            throw new \Exception('L\'événement n\'existe pas');
        }

        $form = $this->formFactory->create(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleEventForm($event, $form);
            $this->entityManager->flush();

            return ['success' => true, 'event' => $event];
        }

        return ['success' => false, 'form' => $form];
    }

    /**
     * Delete event
     *
     * @param integer $id
     * @return void
     */
    public function deleteEvent(int $id): void
    {
        $event = $this->getEventById($id);

        if (!$event) {
            throw new \Exception('L\'événement n\'existe pas');
        }

        // Supprimer image de l'événement
        $file = __DIR__ . '/../../public/uploads/' . $event->getPicture();
        if (file_exists($file) && !is_dir($file)) {
            unlink($file);
        }

        $this->entityManager->remove($event);
        $this->entityManager->flush();
    }
    
    /**
     * Handle event form
     *
     * @param Event $event
     * @param mixed $form
     * @return void
     */
    private function handleEventForm(Event $event, $form): void
    {
        $imageFile = $form->get('picture')->getData();
        $address = $form->get('address')->getData();
        $coordinates = $this->localisator->getLocation($address);
        $event->setLongitude($coordinates[0]);
        $event->setLatitude($coordinates[1]);

        if ($imageFile) {
            $imageFileName = $this->fileUploader->upload($imageFile);
            $event->setPicture($imageFileName);
        }

        foreach ($form->get('ambiance')->getData() as $ambiance) {
            $event->addAmbianceEvent($ambiance);
        }

        foreach ($form->get('specialRegime')->getData() as $special) {
            $event->addSpecialRegimeEvent($special);
        }
    }
}

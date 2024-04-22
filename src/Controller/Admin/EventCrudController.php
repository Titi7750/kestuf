<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Form\EventType;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class EventCrudController extends DashboardController
{
    #[Route('/admin/event', name: 'admin_event')]
    public function listEvent(EntityManagerInterface $entityManagerInterface)
    {
        $events = $entityManagerInterface->getRepository(Event::class)->findAll();

        return $this->render('admin/event/event.html.twig', [
            'events' => $events
        ]);
    }

    #[Route('/admin/event/create', name: 'admin_event_create')]
    public function createEvent(Request $request, EntityManagerInterface $entityManagerInterface, FileUploader $fileUploader)
    {
        $event = new Event();

        $formEvent = $this->createForm(EventType::class, $event);
        $formEvent->handleRequest($request);

        if ($formEvent->isSubmitted() && $formEvent->isValid()) {
            $imageFile = $formEvent->get('picture')->getData();
            $ambiances = $formEvent->get('ambiance')->getData();
            $specialRegime = $formEvent->get('specialRegime')->getData();

            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $event->setPicture($imageFileName);
            }

            $event->addAmbianceEvent($ambiances);
            $event->addSpecialRegimeEvent($specialRegime);

            $entityManagerInterface->persist($event);
            $entityManagerInterface->flush();

            $this->addFlash('success', 'L\'événement a bien été ajouté');
            return $this->redirectToRoute('admin_event');
        }

        return $this->render('admin/event/createEvent.html.twig', [
            'formEvent' => $formEvent
        ]);
    }

    #[Route('/admin/event/show/{id}', name: 'admin_event_show')]
    public function showEvent($id, EntityManagerInterface $entityManagerInterface)
    {
        $event = $entityManagerInterface->getRepository(Event::class)->find($id);

        if (!$event) {
            throw $this->createNotFoundException('L\'événement n\'existe pas');
        }

        return $this->render('admin/event/showEvent.html.twig', [
            'event' => $event
        ]);
    }

    #[Route('/admin/event/update/{id}', name: 'admin_event_update')]
    public function updateEvent(Event $id, Request $request, EntityManagerInterface $entityManagerInterface, FileUploader $fileUploader)
    {
        $event = $entityManagerInterface->getRepository(Event::class)->find($id);

        if (!$event) {
            throw $this->createNotFoundException('L\'événement n\'existe pas');
        }

        $formEvent = $this->createForm(EventType::class, $event);
        $formEvent->handleRequest($request);

        if ($formEvent->isSubmitted() && $formEvent->isValid()) {
            $imageFile = $formEvent->get('picture')->getData();
            $ambiances = $formEvent->get('ambiance')->getData();
            $specialRegime = $formEvent->get('specialRegime')->getData();

            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $event->setPicture($imageFileName);
            }

            $event->addAmbianceEvent($ambiances);
            $event->addSpecialRegimeEvent($specialRegime);

            $entityManagerInterface->flush();

            $this->addFlash('success', 'L\'événement a bien été modifié');
            return $this->redirectToRoute('admin_event');
        }

        return $this->render('admin/event/updateEvent.html.twig', [
            'formEvent' => $formEvent
        ]);
    }

    #[Route('/admin/event/delete/{id}', name: 'admin_event_delete')]
    public function deleteEvent($id, EntityManagerInterface $entityManagerInterface)
    {
        $event = $entityManagerInterface->getRepository(Event::class)->find($id);

        if (!$event) {
            throw $this->createNotFoundException('L\'événement n\'existe pas');
        }

        // Supprimer image de l'événement
        $file = $this->getParameter('image_directory') . '/' . $event->getPicture();
        if (file_exists($file)) {
            unlink($file);
        }

        // Supprimer l'ambiance & le régime spécial de l'événement
        $event->removeAmbianceEvent($event->getAmbianceEvent());
        $event->removeSpecialRegimeEvent($event->getSpecialRegimeEvent());

        $entityManagerInterface->remove($event);
        $entityManagerInterface->flush();

        $this->addFlash('success', 'L\'événement a bien été supprimé');
        return $this->redirectToRoute('admin_event');
    }
}

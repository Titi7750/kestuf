<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Form\EventType;
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
    public function createEvent(Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $event = new Event();

        $formEvent = $this->createForm(EventType::class, $event);
        $formEvent->handleRequest($request);

        if ($formEvent->isSubmitted() && $formEvent->isValid()) {
            $entityManagerInterface->persist($event);
            $entityManagerInterface->flush();

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

        return $this->render('admin/event/showEvent.html.twig', [
            'event' => $event
        ]);
    }

    #[Route('/admin/event/update/{id}', name: 'admin_event_update')]
    public function updateEvent(Event $id, Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $event = $entityManagerInterface->getRepository(Event::class)->find($id);

        $formEvent = $this->createForm(EventType::class, $event);
        $formEvent->handleRequest($request);

        if ($formEvent->isSubmitted() && $formEvent->isValid()) {
            $entityManagerInterface->flush();

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

        $entityManagerInterface->remove($event);
        $entityManagerInterface->flush();

        return $this->redirectToRoute('admin_event');
    }
}

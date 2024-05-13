<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Form\EventType;
use App\Services\FileUploader;
use App\Services\Localisator;
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
    public function createEvent(Request $request, EntityManagerInterface $entityManagerInterface, FileUploader $fileUploader, Localisator $localisator)
    {
        $event = new Event();

        $formEvent = $this->createForm(EventType::class, $event);
        $formEvent->handleRequest($request);

        if ($formEvent->isSubmitted() && $formEvent->isValid()) {
            $imageFile = $formEvent->get('picture')->getData();
            $ambiances = $formEvent->get('ambiance')->getData();
            $specialRegime = $formEvent->get('specialRegime')->getData();

            $address = $formEvent->get('address')->getData();
            $coordinates = $localisator->getLocation($address);
            $event->setLongitude($coordinates[0]);
            $event->setLatitude($coordinates[1]);

            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $event->setPicture($imageFileName);
            }

            foreach ($ambiances as $ambiance) {
                $event->addAmbianceEvent($ambiance);
            }

            foreach ($specialRegime as $special) {
                $event->addSpecialRegimeEvent($special);
            }

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
    public function updateEvent(int $id, Request $request, EntityManagerInterface $entityManagerInterface, FileUploader $fileUploader, Localisator $localisator)
    {
        $event = $entityManagerInterface->getRepository(Event::class)->find($id);

        if (!$event) {
            throw $this->createNotFoundException('L\'événement n\'existe pas');
        }

        $formEvent = $this->createForm(EventType::class, $event);
        $formEvent->handleRequest($request);

        if ($formEvent->isSubmitted() && $formEvent->isValid()) {
            $address = $formEvent->get('address')->getData();
            $coordinates = $localisator->getLocation($address);
            $event->setLongitude($coordinates[0]);
            $event->setLatitude($coordinates[1]);

            $imageFile = $formEvent->get('picture')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $event->setPicture($imageFileName);
            }

            // Update ambiance
            $selectedAmbiances = $formEvent->get('ambiance')->getData();
            foreach ($event->getAmbianceEvent() as $existingAmbiance) {
                if (!$selectedAmbiances->contains($existingAmbiance)) {
                    $event->removeAmbianceEvent($existingAmbiance);
                }
            }

            foreach ($selectedAmbiances as $newAmbiance) {
                if (!$event->getAmbianceEvent()->contains($newAmbiance)) {
                    $event->addAmbianceEvent($newAmbiance);
                }
            }

            // Update special regime
            $selectedSpecialRegime = $formEvent->get('specialRegime')->getData();
            foreach ($event->getSpecialRegimeEvent() as $existingSpecialRegime) {
                if (!$selectedSpecialRegime->contains($existingSpecialRegime)) {
                    $event->removeSpecialRegimeEvent($existingSpecialRegime);
                }
            }

            foreach ($selectedSpecialRegime as $newSpecialRegime) {
                if (!$event->getSpecialRegimeEvent()->contains($newSpecialRegime)) {
                    $event->addSpecialRegimeEvent($newSpecialRegime);
                }
            }

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
        if (file_exists($file) && !is_dir($file)) { // Check that the file exists and is not a directory
            unlink($file);
        }

        $entityManagerInterface->remove($event);
        $entityManagerInterface->flush();

        $this->addFlash('success', 'L\'événement a bien été supprimé');
        return $this->redirectToRoute('admin_event');
    }
}

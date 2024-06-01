<?php

namespace App\Controller\Admin;

use App\Services\Admin\AdminEventService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class EventCrudController extends DashboardController
{
    private $adminEventService;

    public function __construct(AdminEventService $adminEventService)
    {
        $this->adminEventService = $adminEventService;
    }

    /**
     * Display event list
     *
     * @return Response
     */
    #[Route('/admin/event', name: 'admin_event')]
    public function listEvent()
    {
        $events = $this->adminEventService->listEvents();

        return $this->render('admin/event/event.html.twig', [
            'events' => $events
        ]);
    }

    /**
     * Create event
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/event/create', name: 'admin_event_create')]
    public function createEvent(Request $request)
    {
        $result = $this->adminEventService->createEvent($request);

        if ($result['success']) {
            $this->addFlash('success', 'L\'événement a bien été ajouté');
            return $this->redirectToRoute('admin_event');
        }

        return $this->render('admin/event/createEvent.html.twig', [
            'formEvent' => $result['form']->createView()
        ]);
    }

    /**
     * Display event
     *
     * @param integer $id
     * @return Response
     */
    #[Route('/admin/event/show/{id}', name: 'admin_event_show')]
    public function showEvent(int $id)
    {
        $event = $this->adminEventService->getEventById($id);

        if (!$event) {
            throw $this->createNotFoundException('L\'événement n\'existe pas');
        }

        return $this->render('admin/event/showEvent.html.twig', [
            'event' => $event
        ]);
    }

    /**
     * Update event
     *
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/event/update/{id}', name: 'admin_event_update')]
    public function updateEvent(int $id, Request $request)
    {
        $result = $this->adminEventService->updateEvent($id, $request);

        if ($result['success']) {
            $this->addFlash('success', 'L\'événement a bien été modifié');
            return $this->redirectToRoute('admin_event');
        }

        return $this->render('admin/event/updateEvent.html.twig', [
            'formEvent' => $result['form']->createView()
        ]);
    }

    /**
     * Delete event
     *
     * @param integer $id
     * @return void
     */
    #[Route('/admin/event/delete/{id}', name: 'admin_event_delete')]
    public function deleteEvent(int $id)
    {
        try {
            $this->adminEventService->deleteEvent($id);
            $this->addFlash('success', 'L\'événement a bien été supprimé');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('admin_event');
    }
}

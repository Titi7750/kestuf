<?php

// src/Controller/EventController.php
namespace App\Controller;

use App\Entity\CommentEvent;
use App\Form\CommentEventType;
use App\Form\FilterType;
use App\Model\SearchData;
use App\Services\EventService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class EventController extends AbstractController
{
    private $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * Display all events
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/evenement', name: 'app_event')]
    public function index(Request $request): Response
    {
        $filterData = new SearchData();
        $form = $this->createForm(FilterType::class, $filterData);
        $form->handleRequest($request);

        $events = $this->eventService->getFilteredEvents($filterData);

        return $this->render('event/index.html.twig', [
            'events' => $events,
            'form' => $form->createView(),
            'user' => $this->getUser(),
        ]);
    }

    /**
     * Display an event
     *
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    #[Route('/evenement/{id}', name: 'app_event_show')]
    public function showEvent(int $id, Request $request): Response
    {
        $event = $this->eventService->getEventById($id);

        if (!$event) {
            throw $this->createNotFoundException('L\'événement n\'existe pas');
        }

        $outlets = $event->getUserEventOutlet();

        $comments = $event->getCommentEventEvent();
        $newComment = new CommentEvent();
        $commentForm = $this->createForm(CommentEventType::class, $newComment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $this->eventService->addComment($event, $this->getUser(), $newComment);
            $this->addFlash('success', 'Votre commentaire a bien été ajouté');

            return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
        }

        return $this->render('event/show.html.twig', [
            'event' => $event,
            'commentForm' => $commentForm->createView(),
            'comments' => $comments,
            'outlets' => $outlets,
            'user' => $this->getUser(),
        ]);
    }

    /**
     * Give a comment to an event
     *
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    #[Route('/evenement/donner-commentaire/{id}', name: 'app_event_give_comment')]
    public function giveCommentEvent(int $id, Request $request): Response
    {
        $event = $this->eventService->getEventById($id);

        if (!$event) {
            throw $this->createNotFoundException('L\'événement n\'existe pas');
        }

        $newComment = new CommentEvent();
        $commentForm = $this->createForm(CommentEventType::class, $newComment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $this->eventService->addComment($event, $this->getUser(), $newComment);
            $this->addFlash('success', 'Votre commentaire a bien été ajouté');

            return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
        }

        return $this->render('event/give_comment.html.twig', [
            'event' => $event,
            'commentForm' => $commentForm->createView(),
            'user' => $this->getUser(),
        ]);
    }

    /**
     * Add an event to favorites
     *
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    #[Route('/evenement/favoris/{id}', name: 'app_event_favorite')]
    public function favoriteEvent(int $id, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $event = $this->eventService->getEventById($id);
        if (!$event) {
            throw $this->createNotFoundException('Événement non trouvé.');
        }

        $this->eventService->toggleFavorite($user, $event);

        return $this->redirectToRoute('app_event');
    }

    /**
     * Remove an event from favorites
     *
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    #[Route('/evenement/disfavorite/{id}', name: 'app_event_disfavorite')]
    public function disfavoriteEvent(int $id, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $event = $this->eventService->getEventById($id);
        if (!$event) {
            throw $this->createNotFoundException('Événement non trouvé.');
        }

        $this->eventService->toggleFavorite($user, $event);

        return $this->redirectToRoute('app_event');
    }

    /**
     * Participate in an event
     *
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    #[Route('/evenement/participer/{id}', name: 'app_event_participate')]
    public function participateEvent(int $id, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $event = $this->eventService->getEventById($id);
        if (!$event) {
            throw $this->createNotFoundException('Événement non trouvé.');
        }

        $this->eventService->toggleParticipation($user, $event);

        return $this->redirectToRoute('app_event_show', ['id' => $id]);
    }

    /**
     * Outlet an event
     * 
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    #[Route('/evenement/sortir/{id}', name: 'app_event_outlet')]
    public function outletEvent(int $id, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $event = $this->eventService->getEventById($id);
        if (!$event) {
            throw $this->createNotFoundException('Événement non trouvé.');
        }

        $this->eventService->toggleOutlet($user, $event);

        return $this->redirectToRoute('app_event_show', ['id' => $id]);
    }
}

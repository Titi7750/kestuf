<?php

namespace App\Controller;

use App\Entity\CommentEvent;
use App\Form\CommentEventType;
use App\Form\FilterType;
use App\Model\SearchData;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EventController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(EventRepository $eventRepository, Request $request): Response
    {
        $filterData = new SearchData();
        $form = $this->createForm(FilterType::class, $filterData);
        $form->handleRequest($request);

        $events = $eventRepository->findSearch($filterData);

        return $this->render('event/index.html.twig', [
            'events' => $events,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/evenement/{id}', name: 'app_event_show')]
    public function showEvent(EventRepository $eventRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = $eventRepository->find($request->get('id'));

        if (!$event) {
            throw $this->createNotFoundException('L\'événement n\'existe pas');
        }

        $comments = $event->getCommentEventEvent();

        $newComment = new CommentEvent();
        $commentForm = $this->createForm(CommentEventType::class, $newComment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $newComment->setUserCommentEvent($this->getUser());
            $newComment->setEventCommentEvent($event);
            $newComment->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($newComment);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commentaire a bien été ajouté');

            return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
        }

        return $this->render('event/show.html.twig', [
            'event' => $event,
            'commentForm' => $commentForm->createView(),
            'comments' => $comments,
        ]);
    }

    #[Route('/evenement/favoris/{id}', name: 'app_event_favorite')]
    public function favoriteEvent(int $id, EventRepository $eventRepository, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $event = $eventRepository->find($id);
        if (!$event) {
            throw $this->createNotFoundException('Événement non trouvé.');
        }

        if ($user->getEventUserFavorite()->contains($event)) { // contains() est une méthode de Collection qui permet de vérifier si un élément est présent dans la collection
            $user->removeEventUserFavorite($event);
        } else {
            $user->addEventUserFavorite($event);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_event_show', ['id' => $id]);
    }

    #[Route('/evenement/participer/{id}', name: 'app_event_participate')]
    public function participateEvent(int $id, EventRepository $eventRepository, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $event = $eventRepository->find($id);
        if (!$event) {
            throw $this->createNotFoundException('Événement non trouvé.');
        }

        if ($user->getEventUserParticipant()->contains($event)) {
            $user->removeEventUserParticipant($event);
        } else {
            $user->addEventUserParticipant($event);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_event_show', ['id' => $id]);
    }
}

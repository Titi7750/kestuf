<?php

namespace App\Controller;

use App\Entity\CommentEvent;
use App\Form\CommentEventType;
use App\Form\FilterType;
use App\Model\SearchData;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}

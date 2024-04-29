<?php

namespace App\Controller;

use App\Form\FilterType;
use App\Model\SearchData;
use App\Repository\EventRepository;
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
}

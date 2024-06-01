<?php

namespace App\Controller;

use App\Form\FilterType;
use App\Model\SearchData;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    /**
     * Display homepage
     *
     * @return Response
     */
    #[Route('/', name: 'app_welcome')]
    public function index(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_event');
        }

        return $this->render('index.html.twig');
    }

    /**
     * Display favorites events
     *
     * @param Security $security
     * @return Response
     */
    #[Route('/mes-favoris', name: 'app_favorite')]
    public function favorites(Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $favorites = $user->getEventUserFavorite();

        return $this->render('event/favorite_list.html.twig', [
            'favorites' => $favorites,
            'user' => $this->getUser(),
        ]);
    }

    /**
     * Display map
     *
     * @return Response
     */
    #[Route('/carte', name: 'app_map')]
    public function map(): Response
    {
        return $this->render('filter/map.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    /**
     * Filter events
     *
     * @param EventRepository $eventRepository
     * @param Request $request
     * @return Response
     */
    #[Route('/filtrer', name: 'app_filtrer')]
    public function filter(EventRepository $eventRepository, Request $request): Response
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

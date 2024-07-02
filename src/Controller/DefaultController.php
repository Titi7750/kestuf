<?php

namespace App\Controller;

use App\Form\FilterType;
use App\Model\SearchData;
use App\Repository\EventRepository;
use App\Services\EventService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DefaultController extends AbstractController
{
    private $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }
    
    /**
     * Display loading page
     *
     * @return Response
     */
    #[Route('/', name: 'app_loading')]
    public function loading(): Response
    {
        return $this->render('loading.html.twig');
    }

    /**
     * Display homepage
     *
     * @return Response
     */
    #[Route('/connexion-inscription', name: 'app_welcome')]
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
    #[IsGranted('ROLE_USER')]
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
    #[IsGranted('ROLE_USER')]
    #[Route('/carte', name: 'app_map')]
    public function map(): Response
    {
        $filterData = new SearchData();
        $events = $this->eventService->getFilteredEvents($filterData);

        return $this->render('filter/map.html.twig', [
            'events' => $events,
            'user' => $this->getUser(),
        ]);
    }

    /**
     * Get data for map
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/map-data', name: 'app_map_data')]
    public function mapData(): JsonResponse
    {
        $data = $this->eventService->getMapData();

        return new JsonResponse($data);
    }

    /**
     * Filter events
     *
     * @param EventRepository $eventRepository
     * @param Request $request
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/filtre', name: 'app_filter')]
    public function filter(Request $request): Response
    {
        $filterData = new SearchData();
        $formFilter = $this->createForm(FilterType::class, $filterData);
        $formFilter->handleRequest($request);

        return $this->render('filter/filter.html.twig', [
            'formFilter' => $formFilter->createView(),
        ]);
    }

    /**
     * Legal information
     * 
     * @return Response
     */
    #[Route('/mentions-legales', name: 'app_legal_information')]
    public function legalInformation(): Response
    {
        return $this->render('legal_information.html.twig');
    }

    /**
     * Privacy policy
     * 
     * @return Response
     */
    #[Route('/politique-de-confidentialite', name: 'app_privacy_policy')]
    public function privacyPolicy(): Response
    {
        return $this->render('privacy_policy.html.twig');
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_welcome')]
    public function index(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_event');
        }

        return $this->render('index.html.twig');
    }

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
        ]);
    }
}

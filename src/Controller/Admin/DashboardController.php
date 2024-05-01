<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Kestuf\'');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Retour à Kestuf\'', 'fas fa-home', 'app_homepage');

        yield MenuItem::section('Gestion des utilisateurs');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class);

        yield MenuItem::section('Gestion des évènements');
        yield MenuItem::linkToRoute('Évènements', 'fa fa-calendar', 'admin_event');
        yield MenuItem::linkToRoute('Catégories', 'fa fa-list', 'admin_category');
        yield MenuItem::linkToRoute('Ambiances', 'fa fa-music', 'admin_ambiance');
        yield MenuItem::linkToRoute('Régimes spéciaux', 'fa fa-star', 'admin_specialRegime');

        yield MenuItem::section('Gestion des commentaires');
        yield MenuItem::linkToRoute('Commentaires Évènements', 'fa fa-comment', 'admin_comment');
    }
}

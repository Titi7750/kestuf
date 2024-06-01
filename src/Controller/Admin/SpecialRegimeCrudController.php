<?php

// src/Controller/Admin/SpecialRegimeCrudController.php
namespace App\Controller\Admin;

use App\Services\Admin\AdminSpecialRegimeService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class SpecialRegimeCrudController extends DashboardController
{
    private $adminSpecialRegimeService;

    public function __construct(AdminSpecialRegimeService $adminSpecialRegimeService)
    {
        $this->adminSpecialRegimeService = $adminSpecialRegimeService;
    }

    /**
     * Display special regime list
     *
     * @return Response
     */
    #[Route('/admin/specialRegime', name: 'admin_specialRegime')]
    public function listSpecialRegime()
    {
        $specialRegimes = $this->adminSpecialRegimeService->listSpecialRegimes();

        return $this->render('admin/specialRegime/specialRegime.html.twig', [
            'specialRegimes' => $specialRegimes
        ]);
    }

    /**
     * Create special regime
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/specialRegime/create', name: 'admin_specialRegime_create')]
    public function createSpecialRegime(Request $request)
    {
        $result = $this->adminSpecialRegimeService->createSpecialRegime($request);

        if ($result['success']) {
            $this->addFlash('success', 'Le régime spécial a bien été ajouté');
            return $this->redirectToRoute('admin_specialRegime');
        }

        return $this->render('admin/specialRegime/createSpecialRegime.html.twig', [
            'formSpecialRegime' => $result['form']->createView()
        ]);
    }

    /**
     * Display special regime
     *
     * @param integer $id
     * @return Response
     */
    #[Route('/admin/specialRegime/show/{id}', name: 'admin_specialRegime_show')]
    public function showSpecialRegime(int $id)
    {
        $specialRegime = $this->adminSpecialRegimeService->getSpecialRegimeById($id);

        if (!$specialRegime) {
            throw $this->createNotFoundException('Le régime spécial n\'existe pas');
        }

        return $this->render('admin/specialRegime/showSpecialRegime.html.twig', [
            'specialRegime' => $specialRegime
        ]);
    }

    /**
     * Update special regime
     *
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/specialRegime/update/{id}', name: 'admin_specialRegime_update')]
    public function updateSpecialRegimeForm(int $id, Request $request)
    {
        $result = $this->adminSpecialRegimeService->updateSpecialRegime($id, $request);

        if ($result['success']) {
            $this->addFlash('success', 'Le régime spécial a bien été modifié');
            return $this->redirectToRoute('admin_specialRegime');
        }

        return $this->render('admin/specialRegime/updateSpecialRegime.html.twig', [
            'formSpecialRegime' => $result['form']->createView()
        ]);
    }

    /**
     * Delete special regime
     *
     * @param integer $id
     * @return Response
     */
    #[Route('/admin/specialRegime/delete/{id}', name: 'admin_specialRegime_delete')]
    public function deleteSpecialRegime(int $id)
    {
        try {
            $this->adminSpecialRegimeService->deleteSpecialRegime($id);
            $this->addFlash('success', 'Le régime spécial a bien été supprimé');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('admin_specialRegime');
    }
}

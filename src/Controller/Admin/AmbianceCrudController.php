<?php

namespace App\Controller\Admin;

use App\Services\Admin\AdminAmbianceService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class AmbianceCrudController extends DashboardController
{
    private $adminAmbianceService;

    public function __construct(AdminAmbianceService $adminAmbianceService)
    {
        $this->adminAmbianceService = $adminAmbianceService;
    }

    /**
     * Display ambiance list
     *
     * @return Response
     */
    #[Route('/admin/ambiance', name: 'admin_ambiance')]
    public function listAmbiance()
    {
        $ambiances = $this->adminAmbianceService->listAmbiances();

        return $this->render('admin/ambiance/ambiance.html.twig', [
            'ambiances' => $ambiances
        ]);
    }

    /**
     * Create ambiance
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/ambiance/create', name: 'admin_ambiance_create')]
    public function createAmbiance(Request $request)
    {
        $result = $this->adminAmbianceService->createAmbiance($request);

        if ($result['success']) {
            $this->addFlash('success', 'L\'ambiance a bien été ajoutée');
            return $this->redirectToRoute('admin_ambiance');
        }

        return $this->render('admin/ambiance/createAmbiance.html.twig', [
            'formAmbiance' => $result['form']->createView()
        ]);
    }

    /**
     * Display ambiance
     *
     * @param integer $id
     * @return Response
     */
    #[Route('/admin/ambiance/show/{id}', name: 'admin_ambiance_show')]
    public function showAmbiance(int $id)
    {
        $ambiance = $this->adminAmbianceService->getAmbianceById($id);

        if (!$ambiance) {
            throw $this->createNotFoundException('L\'ambiance n\'existe pas');
        }

        return $this->render('admin/ambiance/showAmbiance.html.twig', [
            'ambiance' => $ambiance
        ]);
    }

    /**
     * Update ambiance form
     *
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/ambiance/update/{id}', name: 'admin_ambiance_update')]
    public function updateAmbianceForm(int $id, Request $request)
    {
        $result = $this->adminAmbianceService->updateAmbiance($id, $request);

        if ($result['success']) {
            $this->addFlash('success', 'L\'ambiance a bien été modifiée');
            return $this->redirectToRoute('admin_ambiance');
        }

        return $this->render('admin/ambiance/updateAmbiance.html.twig', [
            'formAmbiance' => $result['form']->createView()
        ]);
    }

    /**
     * Delete ambiance
     *
     * @param integer $id
     * @return Response
     */
    #[Route('/admin/ambiance/delete/{id}', name: 'admin_ambiance_delete')]
    public function deleteAmbiance(int $id)
    {
        try {
            $this->adminAmbianceService->deleteAmbiance($id);
            $this->addFlash('success', 'L\'ambiance a bien été supprimée');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('admin_ambiance');
    }
}

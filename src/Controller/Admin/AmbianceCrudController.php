<?php

namespace App\Controller\Admin;

use App\Entity\Ambiance;
use App\Form\AmbianceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class AmbianceCrudController extends DashboardController
{
    #[Route('/admin/ambiance', name: 'admin_ambiance')]
    public function listAmbiance(EntityManagerInterface $entityManagerInterface)
    {
        $ambiances = $entityManagerInterface->getRepository(Ambiance::class)->findAll();

        return $this->render('admin/ambiance/ambiance.html.twig', [
            'ambiances' => $ambiances
        ]);
    }

    #[Route('/admin/ambiance/create', name: 'admin_ambiance_create')]
    public function createAmbiance(Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $ambiance = new ambiance();

        $formAmbiance = $this->createForm(AmbianceType::class, $ambiance);
        $formAmbiance->handleRequest($request);

        if ($formAmbiance->isSubmitted() && $formAmbiance->isValid()) {
            $entityManagerInterface->persist($ambiance);
            $entityManagerInterface->flush();

            $this->addFlash('success', 'L\'ambiance a bien été ajouté');
            return $this->redirectToRoute('admin_ambiance');
        }

        return $this->render('admin/ambiance/createAmbiance.html.twig', [
            'formAmbiance' => $formAmbiance
        ]);
    }

    #[Route('/admin/ambiance/show/{id}', name: 'admin_ambiance_show')]
    public function showAmbiance($id, EntityManagerInterface $entityManagerInterface)
    {
        $ambiance = $entityManagerInterface->getRepository(Ambiance::class)->find($id);

        if (!$ambiance) {
            throw $this->createNotFoundException('L\'ambiance n\'existe pas');
        }

        return $this->render('admin/ambiance/showAmbiance.html.twig', [
            'ambiance' => $ambiance
        ]);
    }

    #[Route('/admin/ambiance/update/{id}', name: 'admin_ambiance_update')]
    public function updateAmbianceForm($id, Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $ambiance = $entityManagerInterface->getRepository(Ambiance::class)->find($id);

        if (!$ambiance) {
            throw $this->createNotFoundException('L\'ambiance n\'existe pas');
        }

        $formAmbiance = $this->createForm(AmbianceType::class, $ambiance);
        $formAmbiance->handleRequest($request);

        if ($formAmbiance->isSubmitted() && $formAmbiance->isValid()) {
            $entityManagerInterface->flush();

            $this->addFlash('success', 'L\'ambiance a bien été modifié');
            return $this->redirectToRoute('admin_ambiance');
        }

        return $this->render('admin/ambiance/updateAmbiance.html.twig', [
            'formAmbiance' => $formAmbiance
        ]);
    }

    #[Route('/admin/ambiance/delete/{id}', name: 'admin_ambiance_delete')]
    public function deleteAmbiance($id, EntityManagerInterface $entityManagerInterface)
    {
        $ambiance = $entityManagerInterface->getRepository(Ambiance::class)->find($id);

        if (!$ambiance) {
            throw $this->createNotFoundException('L\'ambiance n\'existe pas');
        }

        $entityManagerInterface->remove($ambiance);
        $entityManagerInterface->flush();

        $this->addFlash('success', 'L\'ambiance a bien été supprimé');
        return $this->redirectToRoute('admin_ambiance');
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\SpecialRegime;
use App\Form\SpecialRegimeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class SpecialRegimeCrudController extends DashboardController
{
    #[Route('/admin/specialRegime', name: 'admin_specialRegime')]
    public function listSpecialRegime(EntityManagerInterface $entityManagerInterface)
    {
        $specialRegimes = $entityManagerInterface->getRepository(SpecialRegime::class)->findAll();

        return $this->render('admin/specialRegime/specialRegime.html.twig', [
            'specialRegimes' => $specialRegimes
        ]);
    }

    #[Route('/admin/specialRegime/create', name: 'admin_specialRegime_create')]
    public function createSpecialRegime(Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $specialRegime = new SpecialRegime();

        $formSpecialRegime = $this->createForm(SpecialRegimeType::class, $specialRegime);
        $formSpecialRegime->handleRequest($request);

        if ($formSpecialRegime->isSubmitted() && $formSpecialRegime->isValid()) {
            $entityManagerInterface->persist($specialRegime);
            $entityManagerInterface->flush();

            $this->addFlash('success', 'Le régime spécial a bien été ajouté');
            return $this->redirectToRoute('admin_specialRegime');
        }

        return $this->render('admin/specialRegime/createSpecialRegime.html.twig', [
            'formSpecialRegime' => $formSpecialRegime
        ]);
    }

    #[Route('/admin/specialRegime/show/{id}', name: 'admin_specialRegime_show')]
    public function showSpecialRegime($id, EntityManagerInterface $entityManagerInterface)
    {
        $specialRegime = $entityManagerInterface->getRepository(SpecialRegime::class)->find($id);

        if (!$specialRegime) {
            throw $this->createNotFoundException('Le régime spécial n\'existe pas');
        }

        return $this->render('admin/specialRegime/showSpecialRegime.html.twig', [
            'specialRegime' => $specialRegime
        ]);
    }

    #[Route('/admin/specialRegime/update/{id}', name: 'admin_specialRegime_update')]
    public function updateSpecialRegimeForm($id, Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $specialRegime = $entityManagerInterface->getRepository(SpecialRegime::class)->find($id);

        if (!$specialRegime) {
            throw $this->createNotFoundException('Le régime spécial n\'existe pas');
        }

        $formSpecialRegime = $this->createForm(SpecialRegimeType::class, $specialRegime);
        $formSpecialRegime->handleRequest($request);

        if ($formSpecialRegime->isSubmitted() && $formSpecialRegime->isValid()) {
            $entityManagerInterface->flush();

            $this->addFlash('success', 'Le régime spécial a bien été modifié');
            return $this->redirectToRoute('admin_specialRegime');
        }

        return $this->render('admin/specialRegime/updateSpecialRegime.html.twig', [
            'formSpecialRegime' => $formSpecialRegime
        ]);
    }

    #[Route('/admin/specialRegime/delete/{id}', name: 'admin_specialRegime_delete')]
    public function deleteSpecialRegime($id, EntityManagerInterface $entityManagerInterface)
    {
        $specialRegime = $entityManagerInterface->getRepository(SpecialRegime::class)->find($id);

        if (!$specialRegime) {
            throw $this->createNotFoundException('Le régime spécial n\'existe pas');
        }

        $entityManagerInterface->remove($specialRegime);
        $entityManagerInterface->flush();

        $this->addFlash('success', 'Le régime spécial a bien été supprimé');
        return $this->redirectToRoute('admin_specialRegime');
    }
}

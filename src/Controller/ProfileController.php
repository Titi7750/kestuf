<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    #[Route('/profil', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig');
    }

    #[Route('/profil/{id}', name: 'app_profile_update')]
    public function profilUpdate(User $user, Request $request, EntityManagerInterface $entityManagerInterface, FileUploader $fileUploader): Response
    {
        $formProfile = $this->createForm(RegistrationFormType::class, $user);
        $formProfile->handleRequest($request);

        if ($formProfile->isSubmitted() && $formProfile->isValid()) {
            $imageFile = $formProfile->get('picture')->getData();

            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $user->setPicture($imageFileName);
            }

            $entityManagerInterface->flush();

            $this->addFlash('success', 'L\'utilisateur a bien été modifié');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/updateProfile.html.twig', [
           'formProfile' => $formProfile
        ]);
    }
}

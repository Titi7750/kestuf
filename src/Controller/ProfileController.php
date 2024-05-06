<?php

namespace App\Controller;

use App\Entity\CommentUser;
use App\Entity\User;
use App\Form\CommentUserType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
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
    #[Route('/profil/{firstname}', name: 'app_profile')]
    public function index(string $firstname, UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->findOneBy(['firstname' => $firstname]);

        if (!$user) {
            throw $this->createNotFoundException('L\'utilisateur n\'existe pas');
        }

        $comments = $user->getUserReceiveComment();

        $newComment = new CommentUser();
        $commentForm = $this->createForm(CommentUserType::class, $newComment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $newComment->setUserSendComment($this->getUser());
            $newComment->setUserReceiveComment($user);
            $newComment->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($newComment);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commentaire a bien été ajouté');

            return $this->redirectToRoute('app_profile', ['firstname' => $user->getFirstname()]);
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'commentForm' => $commentForm->createView(),
            'comments' => $comments
        ]);
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

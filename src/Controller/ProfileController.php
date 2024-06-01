<?php

namespace App\Controller;

use App\Entity\CommentUser;
use App\Entity\User;
use App\Form\CommentUserType;
use App\Form\RegistrationFormType;
use App\Services\ProfileService;
use App\Services\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    private $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Display user profile
     *
     * @param string $firstname
     * @param Request $request
     * @return Response
     */
    #[Route('/profil/{firstname}', name: 'app_profile')]
    public function index(string $firstname, Request $request): Response
    {
        $user = $this->profileService->getUserByFirstname($firstname);

        if (!$user) {
            throw $this->createNotFoundException('L\'utilisateur n\'existe pas');
        }

        $comments = $user->getUserReceiveComment();

        $newComment = new CommentUser();
        $commentForm = $this->createForm(CommentUserType::class, $newComment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $this->profileService->addComment($user, $this->getUser(), $newComment);
            $this->addFlash('success', 'Votre commentaire a bien été ajouté');

            return $this->redirectToRoute('app_profile', ['firstname' => $user->getFirstname()]);
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'commentForm' => $commentForm->createView(),
            'comments' => $comments
        ]);
    }

    /**
     * Update user profile
     *
     * @param User $user
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     */
    #[Route('/profil/{id}', name: 'app_profile_update')]
    public function profilUpdate(User $user, Request $request, FileUploader $fileUploader): Response
    {
        $formProfile = $this->createForm(RegistrationFormType::class, $user);
        $formProfile->handleRequest($request);

        if ($formProfile->isSubmitted() && $formProfile->isValid()) {
            $imageFile = $formProfile->get('picture')->getData();

            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $user->setPicture($imageFileName);
            }

            $this->profileService->updateUser($user);

            $this->addFlash('success', 'L\'utilisateur a bien été modifié');
            return $this->redirectToRoute('app_profile', ['firstname' => $user->getFirstname()]);
        }

        return $this->render('profile/updateProfile.html.twig', [
            'formProfile' => $formProfile->createView(),
        ]);
    }
}

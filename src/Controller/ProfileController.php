<?php

namespace App\Controller;

use App\Entity\CommentUser;
use App\Entity\User;
use App\Form\CommentUserType;
use App\Form\UpdateProfileType;
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
     * @param string $surname
     * @param Request $request
     * @return Response
     */
    #[Route('/profil/{surname}', name: 'app_profile')]
    public function index(string $surname, Request $request): Response
    {
        $user = $this->profileService->getUserBysurname($surname);

        if (!$user) {
            throw $this->createNotFoundException('L\'utilisateur n\'existe pas');
        }

        $comments = $user->getUserReceiveComment();
        $outlets = $user->getEventUserOutlet();

        $newComment = new CommentUser();
        $commentForm = $this->createForm(CommentUserType::class, $newComment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $this->profileService->addComment($user, $this->getUser(), $newComment);
            $this->addFlash('success', 'Votre commentaire a bien été ajouté');

            return $this->redirectToRoute('app_profile', ['surname' => $user->getSurname()]);
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'commentForm' => $commentForm->createView(),
            'comments' => $comments,
            'outlets' => $outlets,
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
    #[Route('/profil/update/{surname}', name: 'app_profile_update')]
    public function profilUpdate(string $surname, Request $request, FileUploader $fileUploader): Response
    {
        $user = $this->profileService->getUserBysurname($surname);

        if (!$user) {
            throw $this->createNotFoundException('L\'utilisateur n\'existe pas');
        }

        $currentUser = $this->getUser();
        if ($currentUser->getId() !== $user->getId()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier ce profil');
        }

        $formProfile = $this->createForm(UpdateProfileType::class, $user);
        $formProfile->handleRequest($request);

        if ($formProfile->isSubmitted() && $formProfile->isValid()) {
            $imageFile = $formProfile->get('picture')->getData();

            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $user->setPicture($imageFileName);
            }

            $this->profileService->updateUser($user);

            $this->addFlash('success', 'L\'utilisateur a bien été modifié');
            return $this->redirectToRoute('app_profile', ['surname' => $user->getSurname()]);
        }

        return $this->render('profile/updateProfile.html.twig', [
            'formProfile' => $formProfile->createView(),
            'user' => $user,
        ]);
    }

    /**
     * Give a comment to an user
     * 
     * @param string $surname
     * @param Request $request
     * @return Response
     */
    #[Route('/profil/donner-commentaire/{surname}', name: 'app_profile_give_comment')]
    public function giveCommentUser(string $surname, Request $request): Response
    {
        $user = $this->profileService->getUserBysurname($surname);

        if (!$user) {
            throw $this->createNotFoundException('L\'utilisateur n\'existe pas');
        }

        $newComment = new CommentUser();
        $commentForm = $this->createForm(CommentUserType::class, $newComment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $this->profileService->addComment($user, $this->getUser(), $newComment);
            $this->addFlash('success', 'Votre commentaire a bien été ajouté');

            return $this->redirectToRoute('app_profile', ['surname' => $user->getSurname()]);
        }

        return $this->render('profile/give_comment.html.twig', [
            'user' => $user,
            'commentForm' => $commentForm->createView(),
        ]);
    }
}

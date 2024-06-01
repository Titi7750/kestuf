<?php

namespace App\Services\Admin;

use App\Entity\SpecialRegime;
use App\Form\SpecialRegimeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class AdminSpecialRegimeService
{
    private $entityManager;
    private $formFactory;

    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    /**
     * List all special regimes
     *
     * @return void
     */
    public function listSpecialRegimes()
    {
        return $this->entityManager->getRepository(SpecialRegime::class)->findAll();
    }

    /**
     * Get a special regime by its id
     *
     * @param integer $id
     * @return SpecialRegime|null
     */
    public function getSpecialRegimeById(int $id): ?SpecialRegime
    {
        return $this->entityManager->getRepository(SpecialRegime::class)->find($id);
    }

    /**
     * Create a new special regime
     *
     * @param Request $request
     * @return array
     */
    public function createSpecialRegime(Request $request): array
    {
        $specialRegime = new SpecialRegime();
        $form = $this->formFactory->create(SpecialRegimeType::class, $specialRegime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($specialRegime);
            $this->entityManager->flush();

            return ['success' => true, 'specialRegime' => $specialRegime];
        }

        return ['success' => false, 'form' => $form];
    }

    /**
     * Update a special regime
     *
     * @param integer $id
     * @param Request $request
     * @return array
     */
    public function updateSpecialRegime(int $id, Request $request): array
    {
        $specialRegime = $this->getSpecialRegimeById($id);

        if (!$specialRegime) {
            throw new \Exception('Le régime spécial n\'existe pas');
        }

        $form = $this->formFactory->create(SpecialRegimeType::class, $specialRegime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return ['success' => true, 'specialRegime' => $specialRegime];
        }

        return ['success' => false, 'form' => $form];
    }

    /**
     * Delete a special regime
     *
     * @param integer $id
     * @throws \Exception
     */
    public function deleteSpecialRegime(int $id): void
    {
        $specialRegime = $this->getSpecialRegimeById($id);

        if (!$specialRegime) {
            throw new \Exception('Le régime spécial n\'existe pas');
        }

        $this->entityManager->remove($specialRegime);
        $this->entityManager->flush();
    }
}

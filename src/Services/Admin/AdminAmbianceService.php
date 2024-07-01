<?php

namespace App\Services\Admin;

use App\Entity\Ambiance;
use App\Form\AmbianceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class AdminAmbianceService
{
    private $entityManager;
    private $formFactory;

    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    /**
     * List all ambiances
     *
     * @return void
     */
    public function listAmbiances()
    {
        return $this->entityManager->getRepository(Ambiance::class)->findAll();
    }

    /**
     * Get ambiance by id
     *
     * @param integer $id
     * @return Ambiance|null
     */
    public function getAmbianceById(int $id): ?Ambiance
    {
        return $this->entityManager->getRepository(Ambiance::class)->find($id);
    }

    /**
     * Create ambiance
     *
     * @param Request $request
     * @return array
     */
    public function createAmbiance(Request $request): array
    {
        $ambiance = new Ambiance();
        $form = $this->formFactory->create(AmbianceType::class, $ambiance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($ambiance);
            $this->entityManager->flush();

            return ['success' => true, 'ambiance' => $ambiance];
        }

        return ['success' => false, 'form' => $form];
    }

    /**
     * Update ambiance
     *
     * @param integer $id
     * @param Request $request
     * @return array
     */
    public function updateAmbiance(int $id, Request $request): array
    {
        $ambiance = $this->getAmbianceById($id);

        if (!$ambiance) {
            throw new \Exception('L\'ambiance n\'existe pas');
        }

        $form = $this->formFactory->create(AmbianceType::class, $ambiance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($ambiance);
            $this->entityManager->flush();

            return ['success' => true, 'ambiance' => $ambiance];
        }

        return ['success' => false, 'form' => $form];
    }

    /**
     * Delete ambiance
     *
     * @param integer $id
     * @return void
     */
    public function deleteAmbiance(int $id): void
    {
        $ambiance = $this->getAmbianceById($id);

        if (!$ambiance) {
            throw new \Exception('L\'ambiance n\'existe pas');
        }

        $this->entityManager->remove($ambiance);
        $this->entityManager->flush();
    }
}

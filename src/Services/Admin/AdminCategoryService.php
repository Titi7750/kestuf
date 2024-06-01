<?php

namespace App\Services\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class AdminCategoryService
{
    private $entityManager;
    private $formFactory;

    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    /**
     * List all categories
     *
     * @return void
     */
    public function listCategories()
    {
        return $this->entityManager->getRepository(Category::class)->findAll();
    }

    /**
     * Get a category by its id
     *
     * @param integer $id
     * @return Category|null
     */
    public function getCategoryById(int $id): ?Category
    {
        return $this->entityManager->getRepository(Category::class)->find($id);
    }

    /**
     * Create a new category
     *
     * @param Request $request
     * @return array
     */
    public function createCategory(Request $request): array
    {
        $category = new Category();
        $form = $this->formFactory->create(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            return ['success' => true, 'category' => $category];
        }

        return ['success' => false, 'form' => $form];
    }

    /**
     * Update a category
     *
     * @param integer $id
     * @param Request $request
     * @return array
     */
    public function updateCategory(int $id, Request $request): array
    {
        $category = $this->getCategoryById($id);

        if (!$category) {
            throw new \Exception('La catégorie n\'existe pas');
        }

        $form = $this->formFactory->create(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return ['success' => true, 'category' => $category];
        }

        return ['success' => false, 'form' => $form];
    }

    /**
     * Delete a category
     *
     * @param integer $id
     * @return void
     */
    public function deleteCategory(int $id): void
    {
        $category = $this->getCategoryById($id);

        if (!$category) {
            throw new \Exception('La catégorie n\'existe pas');
        }

        $this->entityManager->remove($category);
        $this->entityManager->flush();
    }
}

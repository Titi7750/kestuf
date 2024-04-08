<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class CategoryCrudController extends DashboardController
{
    #[Route('/admin/category', name: 'admin_category')]
    public function listCategory(EntityManagerInterface $entityManagerInterface)
    {
        $categories = $entityManagerInterface->getRepository(Category::class)->findAll();

        return $this->render('admin/category/category.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/admin/category/create', name: 'admin_category_create')]
    public function createCategory(Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $category = new Category();

        $formCategory = $this->createForm(CategoryType::class, $category);
        $formCategory->handleRequest($request);

        if ($formCategory->isSubmitted() && $formCategory->isValid()) {
            $entityManagerInterface->persist($category);
            $entityManagerInterface->flush();

            $this->addFlash('success', 'La catégorie a bien été ajouté');
            return $this->redirectToRoute('admin_category');
        }

        return $this->render('admin/category/createCategory.html.twig', [
            'formCategory' => $formCategory
        ]);
    }

    #[Route('/admin/category/show/{id}', name: 'admin_category_show')]
    public function showCategory($id, EntityManagerInterface $entityManagerInterface)
    {
        $category = $entityManagerInterface->getRepository(Category::class)->find($id);

        if (!$category) {
            throw $this->createNotFoundException('La catégorie n\'existe pas');
        }

        return $this->render('admin/category/showCategory.html.twig', [
            'category' => $category
        ]);
    }

    #[Route('/admin/category/update/{id}', name: 'admin_category_update')]
    public function updateCategoryForm($id, Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $category = $entityManagerInterface->getRepository(Category::class)->find($id);

        if (!$category) {
            throw $this->createNotFoundException('La catégorie n\'existe pas');
        }

        $formCategory = $this->createForm(CategoryType::class, $category);
        $formCategory->handleRequest($request);

        if ($formCategory->isSubmitted() && $formCategory->isValid()) {
            $entityManagerInterface->flush();

            $this->addFlash('success', 'La catégorie a bien été modifié');
            return $this->redirectToRoute('admin_category');
        }

        return $this->render('admin/category/updateCategory.html.twig', [
            'formCategory' => $formCategory
        ]);
    }

    #[Route('/admin/category/delete/{id}', name: 'admin_category_delete')]
    public function deleteCategory($id, EntityManagerInterface $entityManagerInterface)
    {
        $category = $entityManagerInterface->getRepository(Category::class)->find($id);

        if (!$category) {
            throw $this->createNotFoundException('La catégorie n\'existe pas');
        }

        $entityManagerInterface->remove($category);
        $entityManagerInterface->flush();

        $this->addFlash('success', 'La catégorie a bien été supprimé');
        return $this->redirectToRoute('admin_category');
    }
}

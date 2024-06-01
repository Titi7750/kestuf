<?php

namespace App\Controller\Admin;

use App\Services\Admin\AdminCategoryService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class CategoryCrudController extends DashboardController
{
    private $adminCategoryService;

    public function __construct(AdminCategoryService $adminCategoryService)
    {
        $this->adminCategoryService = $adminCategoryService;
    }

    /**
     * Display category list
     *
     * @return Response
     */
    #[Route('/admin/category', name: 'admin_category')]
    public function listCategory()
    {
        $categories = $this->adminCategoryService->listCategories();

        return $this->render('admin/category/category.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * Create category
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/category/create', name: 'admin_category_create')]
    public function createCategory(Request $request)
    {
        $result = $this->adminCategoryService->createCategory($request);

        if ($result['success']) {
            $this->addFlash('success', 'La catégorie a bien été ajoutée');
            return $this->redirectToRoute('admin_category');
        }

        return $this->render('admin/category/createCategory.html.twig', [
            'formCategory' => $result['form']->createView()
        ]);
    }

    /**
     * Display category
     *
     * @param integer $id
     * @return Response
     */
    #[Route('/admin/category/show/{id}', name: 'admin_category_show')]
    public function showCategory(int $id)
    {
        $category = $this->adminCategoryService->getCategoryById($id);

        if (!$category) {
            throw $this->createNotFoundException('La catégorie n\'existe pas');
        }

        return $this->render('admin/category/showCategory.html.twig', [
            'category' => $category
        ]);
    }

    /**
     * Update category form
     *
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/category/update/{id}', name: 'admin_category_update')]
    public function updateCategoryForm(int $id, Request $request)
    {
        $result = $this->adminCategoryService->updateCategory($id, $request);

        if ($result['success']) {
            $this->addFlash('success', 'La catégorie a bien été modifiée');
            return $this->redirectToRoute('admin_category');
        }

        return $this->render('admin/category/updateCategory.html.twig', [
            'formCategory' => $result['form']->createView()
        ]);
    }

    /**
     * Delete category
     *
     * @param integer $id
     * @return Response
     */
    #[Route('/admin/category/delete/{id}', name: 'admin_category_delete')]
    public function deleteCategory(int $id)
    {
        try {
            $this->adminCategoryService->deleteCategory($id);
            $this->addFlash('success', 'La catégorie a bien été supprimée');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('admin_category');
    }
}

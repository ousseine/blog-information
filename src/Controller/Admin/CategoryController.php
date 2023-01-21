<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[isGranted("ROLE_ADMIN")]
#[Route('/admin/category')]
class CategoryController extends AbstractController
{
    private CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('/', name: 'app_admin_category_index')]
    public function index(): Response
    {
        return $this->render('admin/category/index.html.twig', [
            'controller_name' => 'Les catégories',
            'categories' => $this->repository->findAllByDesc()
        ]);
    }

    #[Route('/edit/{id}', name: 'app_admin_category_edit', methods: ['GET', 'POST'])]
    public function edit(Category $category, Request $request): Response
    {
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->repository->add($category, true);

            return $this->redirectToRoute('app_admin_category_index');
        }

        return $this->renderForm('admin/category/edit.html.twig', [
            'controller_name' => 'Modifier la catégorie',
            'form' => $form
        ]);
    }

    #[Route('/add', name: 'app_admin_category_add', methods: ['GET','POST'])]
    public function add(Request $request): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->add($category, true);

            return $this->redirectToRoute('app_admin_category_index');
        }

        return $this->renderForm('admin/category/add.html.twig', [
            'controller_name' => 'Ajouter une catégorie',
            'form' => $form
        ]);
    }

    #[Route('/delete/{id}', name: 'app_admin_category_delete', methods: ['POST'])]
    public function delete(Category $category, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $this->repository->remove($category, true);
        }

        return $this->redirectToRoute('app_admin_category_index');
    }
}

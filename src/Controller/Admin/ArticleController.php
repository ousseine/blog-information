<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[isGranted("ROLE_ADMIN")]
#[Route('/admin')]
class ArticleController extends AbstractController
{
    private ArticleRepository $repository;
    private PaginatorInterface $paginator;

    public function __construct(ArticleRepository $repository, PaginatorInterface $paginator)
    {
        $this->repository = $repository;
        $this->paginator = $paginator;
    }

    #[Route('/', name: 'app_admin_article_index')]
    public function index(Request $request): Response
    {
        $articles = $this->paginator->paginate(
            $this->repository->findAllByDesc(),
            $request->query->getInt('page', 1),
            30
        );

        return $this->render('admin/article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articles
        ]);
    }

    #[Route('/show/{slug}', name: 'app_admin_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('admin/article/show.html.twig', [
            'controller_name' => "$article",
            'article' => $article
        ]);
    }

    #[Route('/edit/{id}', name: 'app_admin_article_edit', methods: ['GET', 'POST'])]
    public function edit(Article $article, Request $request): Response
    {
        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->repository->add($article, true);

            $this->addFlash('success', "Votre article viens d'être modifier ):");

            return $this->redirectToRoute('app_admin_article_show', ['slug' => $article->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/article/edit.html.twig', [
            'controller_name' => 'Modifier un article',
            'form' => $form
        ]);
    }

    #[Route('/add', name: 'app_admin_article_add', methods: ['GET', 'POST'])]
    public function add(Request $request, SluggerInterface $slugger): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setSlug($slugger->slug($article->getTitle()));
            $this->repository->add($article, true);

            $this->addFlash('success', "Un article vient d'être enregistrer ):");

            return $this->redirectToRoute('app_admin_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return  $this->renderForm('admin/article/add.html.twig', [
            'controller_name' => 'Ajouter un article',
            'form' => $form
        ]);
    }

    #[Route('/delete/{id}', name: 'app_admin_article_delete', methods: ['POST'])]
    public function delete(Article $article, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $this->repository->remove($article, true);
        }

        $this->addFlash('success', "Votre article vient d'être supprimer :)");

        return $this->redirectToRoute('app_admin_article_index', [], Response::HTTP_SEE_OTHER);
    }
}

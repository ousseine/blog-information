<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private ArticleRepository $articleRepository;
    private PaginatorInterface $paginator;

    public function __construct(ArticleRepository $articleRepository, PaginatorInterface $paginator)
    {
        $this->articleRepository = $articleRepository;
        $this->paginator = $paginator;
    }

    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        $articles = $this->paginator->paginate(
            $this->articleRepository->findAllByDesc(),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('home/index.html.twig', [
            'controller_name' => 'Nos articles',
            'articles' => $articles
        ]);
    }

    #[Route('/category/{name}', name: 'app_category')]
    public function ArticleCategory(Category $category, Request $request): Response
    {
        $articles = $this->paginator->paginate(
            $category->getArticles(),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('home/index.html.twig', [
            'controller_name' => "Catégorie : $category",
            'articles' => $articles,
            'category' => $category
        ]);
    }

    #[Route('/show/{slug}', name: 'app_show')]
    public function show(Article $article, Request $request, CommentRepository $repository): Response
    {
        // poster un commentaire
        $comment = new Comment();

        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $comment->setArticle($article);

            // on récupère l'id du commentaire parent
            $parentId = $form->get('parent_id')->getData();
            if( $parentId ) $comment->setParent($repository->find($parentId));

            $repository->add($comment, true);

            $this->addFlash('success', "Super, votre commentaire vient d'être enregistrer !");

            return $this->redirectToRoute('app_show', ['slug' => $article->getSlug()]);
        }

        return $this->render('article/show.html.twig', [
            'controller_name' => '',
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    #[Route('/search', name: 'app_search', methods: ['GET', 'POST'])]
    public function search(Request $request): Response
    {
        $query = $request->query->get('query');

        $articles = $this->paginator->paginate(
            $this->articleRepository->findBySearch($query),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('home/index.html.twig', [
            'controller_name' => "Résultat de la recherche : {$query}",
            'query' => $query,
            'articles' => $articles
        ]);
    }
}

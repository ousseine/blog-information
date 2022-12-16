<?php

namespace App\Twig;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SidebarExtension extends AbstractExtension
{
    // le but est de créer un sidebar qui peut être utiliser partout dans notre site internet
    // notre sidebar contiendra : les dernier posts (5), les catégories

    private ArticleRepository $articleRepository;
    private CategoryRepository $categoryRepository;
    private Environment $twig;
    private TagAwareAdapterInterface $cache;

    public function __construct(
        ArticleRepository $articleRepository,
        CategoryRepository $categoryRepository,
        Environment $twig,
        TagAwareAdapterInterface $cache
    )
    {
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->twig = $twig;
        $this->cache = $cache;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('sidebar', [$this, 'getSidebar'], ['is_safe' => ['html']])
        ];
    }

    public function getSidebar(): string
    {
        return $this->cache->get('sidebar', function (ItemInterface $item) {
            $item->tag(['articles', 'categories']);
            return $this->renderSidebar();
        });
    }

    private function renderSidebar(): string
    {
        return $this->twig->render('include/sidebar.html.twig', [
            'articles' => $this->articleRepository->findLatest(),
            'categories' => $this->categoryRepository->findAll()
        ]);
    }
}
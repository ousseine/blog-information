<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[isGranted("ROLE_ADMIN")]
#[Route('/admin/comment')]
class CommentController extends AbstractController
{
    private CommentRepository $repository;
    private PaginatorInterface $paginator;

    public function __construct(CommentRepository $repository, PaginatorInterface $paginator)
    {
        $this->repository = $repository;
        $this->paginator = $paginator;
    }

    #[Route('/', name: 'app_admin_comment_index')]
    public function index(Request $request): Response
    {
        $comments = $this->paginator->paginate(
            $this->repository->findAllByDesc(),
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('admin/comment/index.html.twig', [
            'controller_name' => 'Liste des commentaires',
            'comments' => $comments
        ]);
    }

    #[Route('/delete/{id}', name: 'app_admin_comment_delete', methods: ['POST'])]
    public function delete(Comment $comment, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $this->repository->remove($comment, true);
        }

        return $this->redirectToRoute('app_admin_comment_index');
    }
}

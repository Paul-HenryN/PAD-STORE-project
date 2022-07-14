<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'app_articles')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy(['is_available'=>true]);
        return $this->render('Article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/articles/search/{searchToken}', name: 'app_articles_search')]
    public function search(ArticleRepository $articleRepository, $searchToken): JsonResponse
    {

        if($searchToken != 'all') {
            return $this->json($articleRepository->findByNameLike($searchToken));
        }

        return $this->json($articleRepository->findBy(['is_available'=>true]));
    }
}

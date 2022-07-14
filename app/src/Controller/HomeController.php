<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\CommandRepository;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Security $security, CategoryRepository $categoryRepository, CommandRepository $commandRepository, ArticleRepository $articleRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $articles = $articleRepository->findBy(['is_available'=>true]);
        $user = $security->getUser();
        $commands = $commandRepository->findBy(['user' => $user], ['date' => 'DESC'], 3);

        if(!$user)
            return $this->redirectToRoute('app_articles');

        $leftBudgets = $user->getLeftBudgets();
        
        return $this->render('Home/index.html.twig', [
            'categories' => $categories,
            'commands' => $commands,
            'articles' => $articles,
            'leftBudgets' => $leftBudgets,
        ]);
    }
}

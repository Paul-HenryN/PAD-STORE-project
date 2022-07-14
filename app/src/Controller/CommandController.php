<?php

namespace App\Controller;

use App\Entity\Command;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CommandController extends AbstractController
{
    #[Route('/command', name: 'app_command')]
    public function index(): Response
    {
        return $this->render('command/index.html.twig', [
            'controller_name' => 'CommandController',
        ]);
    }

    #[Route('/command/create', name: 'app_command_create')]
    public function create(Security $security, ArticleRepository $articleRepository): Response
    {
        $user = $security->getUser();
        $leftBudgets = $user->getLeftBudgets();
        $articles = $articleRepository->findBy(['is_available'=>true]);
        return $this->render('command/create.html.twig', [
            'articles' => $articles,
            'leftBudgets' => $leftBudgets,
        ]);
    }

    #[Route('/command/store', name: 'app_command_store')]
    public function store(Security $security, Request $request, UserRepository $userRepository, ArticleRepository $articleRepository, CommandRepository $commandRepository): Response
    {
        $amount = 0.0;
        $data = $request->toArray();
        $user = $security->getUser();
        $articles = [];
        $quantities = [];
        $command = new Command();
        $command->setUser($user);
        $command->setDate(date_create());

        foreach ($data as $articleName => $quantity) {
            $article = $articleRepository->findOneBy(['name'=>$articleName]);
            $articles[] = $article;
            $quantities[] = $quantity;
            $amount += $article->getPrice() * $quantity;
        }

        $command->setAmount($amount);
        $command->setArticles($articles);
        $command->setQuantities($quantities);

        $user->addCommand($command);
        $commandRepository->add($command, true);

        return new Response("success");
    }

    #[Route('/command/print/{id}', name: 'app_command_print')]
    public function print($id, CommandRepository $commandRepository) : Response {
        $command = $commandRepository->find($id);
        $commandQuantities = $command->getQuantities();
        $commandArticles = $command->getArticles();
        $quantities = [];

        for ($i=0; $i < count($commandArticles); $i++) { 
            $quantities[$command->getQuantities()[$i]] = $command->getArticles()[$i];
        }

        return $this->render('Command/print.html.twig', [
            'quantities' => $quantities,
            'command' => $command
        ]);
    }
}

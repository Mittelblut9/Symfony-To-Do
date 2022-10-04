<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/', name: 'app_task_homepage')]
    public function homepage(): Response
    {
        $number = random_int(0, 100);

        return $this->render('todo/todo.html.twig', [
            'number' => $number,
        ]);
    }
}
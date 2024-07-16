<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;

class TaskController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('tasks/list', name: 'task_list', methods: ['GET'])]
    public function list(TaskRepository $taskRepository)
    {
        $tasks = $taskRepository->findAll();
        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

    #[Route('/tasks/create', name: 'task_create', methods:['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $task->setAuthor($this->security->getUser());
            $task->setDone(false);
            $task->setCreatedAt(new \DateTime());
            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('tasks/{id}/edit', name: 'task_edit', methods:['GET', 'POST'])]
    public function editAction(Task $task, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/tasks/{id}/toggle', name: 'task_toggle', methods: ['GET'])]
    public function toggleTask(Task $task, EntityManagerInterface $em)
    {
        $task->toggle(!$task->isDone());
        $em->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete', methods: ['DELETE'])]
    public function deleteTask(Task $task, EntityManagerInterface $em)
    {
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}

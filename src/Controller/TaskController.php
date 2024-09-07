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
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TaskController extends AbstractController
{
    private Security $security;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(Security $security, UserPasswordHasherInterface $passwordHasher)
    {
        $this->security = $security;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('tasks/list', name: 'task_list', methods: ['GET'])]
    public function list(TaskRepository $taskRepository)
    {
        $tasks = $taskRepository->findAll();
        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

    #[Route('/tasks/create', name: 'task_create', methods:['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY', message: "Vous devez être connecté pour accéder à cette page.")]
    public function create(Request $request, EntityManagerInterface $em, UserRepository $userRepository)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if(!$this->security->getUser()) {
                $anonymous = $userRepository->findOneByUsername("Anonymous");
            }

            $task->setAuthor($this->security->getUser() ?? $anonymous);
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
    #[IsGranted('IS_AUTHENTICATED_FULLY', message: "Vous devez être connecté pour accéder à cette page.")]
    public function edit(Task $task, Request $request, EntityManagerInterface $em)
    {
        $user = $this->security->getUser();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($form->getData()->getAuthor() !== $task->getAuthor()) {
                $this->addFlash('error', 'Vous ne pouvez pas modifier l\'auteur d\'une tâche.');
                return $this->redirectToRoute('task_list');
            }

            $task->setTitle($form->getData()->getTitle());
            $task->setContent($form->getData()->getContent());
            
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
    #[IsGranted('IS_AUTHENTICATED_FULLY', message: "Vous devez être connecté pour modifier cette fonction.")]
    public function toggle(Task $task, EntityManagerInterface $em)
    {
        $task->toggle(!$task->isDone());
        $em->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    /**
     * Function : Delete a task
     * @param Task $task
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    #[Route('/tasks/{id}/delete', name: 'task_delete', methods: ['DELETE', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY', message: "Vous devez être connecté pour accéder à cette page.")]
    public function delete(Task $task, EntityManagerInterface $em)
    {
        $user = $this->security->getUser();

        // Error if user is not role_admin and user_id is not equals to task author id
        if(!in_array('ROLE_ADMIN', $user->getRoles()) && $user->getId() !== $task->getAuthor()->getId()) {
            $this->addFlash(
               'error',
               'Vous ne pouvez pas supprimer cette tâche.'
            );
            return $this->redirectToRoute('task_list');
        }

        // Admin can delete Anonymous tasks
        if(in_array('ROLE_ADMIN', $user->getRoles()) && $task->getAuthor()->getUsername() == 'Anonymous') {
            $em->remove($task);
            $em->flush();
            $this->addFlash('success', 'La tâche Anonyme a bien été supprimée.');

            return $this->redirectToRoute('task_list');
        }

        // User can delete their task
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}

<?php

namespace App\Tests\Controllers;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TaskControllerTest extends WebTestCase
{
    private $client;
    private $entityManager;
    private $passwordHasher;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        // Create a Mock for passwordHasher
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->passwordHasher->method('hashPassword')
            ->willReturn('hashed_password');
        
        static::getContainer()->set(UserPasswordHasherInterface::class, $this->passwordHasher);
    }

    /**
     * Function to create a test user
     * @return User $user
     */
    private function createUser(): User
    {
        $uniqId = uniqid(3);
        $user = new User();
        $user->setEmail('user_'.$uniqId.'@example.com');
        $user->setRoles(['ROLE_USER']);
        $user->setUsername('User_'.$uniqId);

        $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    /**
     * Create a task for tests
     * @return Task $task
     */
    private function createTask(): Task
    {
        $user = $this->createUser();

        $test_number = uniqid(5);
        $task = new Task();
        $task->setTitle('this is a test number '. $test_number);
        $task->setContent('this is a test content');
        $task->setDone(false);
        $task->setCreatedAt(new \DateTime);
        $task->setAuthor($user);

        $this->entityManager->persist($task);
        $this->entityManager->flush();
        return $task;
    }

    /**
     * Access to the tasks list
     * @return void
     */
    public function testTaskListView(): void
    {
        // Use the profiler to get the template
        $this->client->enableProfiler();

        // Try access to the route
        $crawler = $this->client->request('GET', '/tasks/list');
        $this->assertResponseIsSuccessful();

        $profile = $this->client->getProfile();
        $collector = $profile->getCollector('twig');

        // Verify if it's the right template
        $templates = array_keys($collector->getTemplates());
        $this->assertContains('task/list.html.twig', $templates);
    }

    /**
     * Test : Task form is rendered correctly
     * Can access without authentication
     * @return void
     */
    public function testCreateTaskFormIsRendered(): void
    {
        // Use the profiler to get the template
        $this->client->enableProfiler();

        // Try access to the route
        $crawler = $this->client->request('GET', 'tasks/create');
        $this->assertResponseIsSuccessful('Nous ne pouvons pas accéder à la page tasks/create.');

        $profile = $this->client->getProfile();
        $collector = $profile->getCollector('twig');

        // Verify if it's the right template and template get form
        $templates = array_keys($collector->getTemplates());
        $this->assertContains('task/create.html.twig', $templates);
        $this->assertSelectorExists('form');
    }

    /**
     * Test : Anonymous user can create a task
     * @return void
     */
    public function testAnonymousUserCreateTask(): void
    {
        // Try post the task form
        $crawler = $this->client->request('POST', 'tasks/create');

        $test_number = uniqid(5);
        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'this is a test number '. $test_number,
            'task[content]' => 'this is a test content',
        ]);
        $this->client->submit($form);

        // Verify if the response is a redirection
        $this->assertResponseRedirects('/tasks/list', 302, "La redirection après soumission du formulaire a échoué.");
        $this->client->followRedirect();

        // Verify if template contains an alert success
        $this->assertSelectorTextContains('.alert-success', "La tâche a été bien été ajoutée.");

        // Get the task created and verify if user by default is anonymous
        $task = $this->entityManager->getRepository(Task::class)->findOneBy(['title' => 'this is a test number '. $test_number]);
        $this->assertNotNull($task, "La tâche n'a pas été trouvée.");

        $this->assertEquals("Anonymous", $task->getAuthor()->getUsername(), "La tâche n'est pas associée à l'utilisateur 'Anonymous'.");
    }

    /**
     * Test : Logged in User create a task
     * @return void
     */
    public function testLoggedInUserCreateTask(): void
    {
        $user = $this->createUser();
        $this->client->loginUser($user);

        $crawler = $this->client->request('POST', 'tasks/create');

        $test_number = uniqid(5);
        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'this is a test number '. $test_number,
            'task[content]' => 'this is a test content',
        ]);
        $this->client->submit($form);

        $this->assertResponseRedirects('/tasks/list', 302, "La redirection après soumission du formulaire a échoué.");
        
        $this->client->followRedirect();

        $this->assertSelectorTextContains('.alert-success', "La tâche a été bien été ajoutée.");

        $task = $this->entityManager->getRepository(Task::class)->findOneBy(['title' => 'this is a test number '. $test_number]);
        $this->assertNotNull($task, "La tâche n'a pas été trouvée.");

        $this->assertEquals($user->getId(), $task->getAuthor()->getId(), "La tâche n'est pas associée à l'utilisateur connecté.");
    }

    /**
     * Test : Edit form is rendered
     * @return void
     */
    public function testEditTaskFormIsRendered(): void
    {
        // Use the profiler to get the template
        $this->client->enableProfiler();

        // Create a random task
        $task = $this->createTask();

        // Try access to the route
        $crawler = $this->client->request('GET', 'tasks/'.$task->getId().'/edit');
        $this->assertResponseIsSuccessful('Nous ne pouvons pas accéder à la page tasks/edit.');

        $profile = $this->client->getProfile();
        $collector = $profile->getCollector('twig');

        // Verify if it's the right template and template get form
        $templates = array_keys($collector->getTemplates());
        $this->assertContains('task/edit.html.twig', $templates);
        $this->assertSelectorExists('form');
    }

    /**
     * Test : User can edit a task
     * @return void
     */
    public function testUserCanEditTask(): void
    {
        // Create a random task
        $task = $this->createTask();

        // Request the post edit route
        $crawler = $this->client->request('POST', 'tasks/'.$task->getId().'/edit');

        // Modify the current task
        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => $task->getTitle().' edit',
            'task[content]' => 'this is a test content edit',
        ]);
        $this->client->submit($form);

        $this->assertResponseRedirects('/tasks/list', 302, "La redirection après soumission du formulaire a échoué.");
        
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', "La tâche a bien été modifiée.");
    }

    /**
     * Test : Function Toggle Task
     * @return void
     */
    public function testToggleTask(): void
    {
        $task = $this->createTask();

        $old_toggle = $task->isDone();

        $this->client->request('GET', 'tasks/'.$task->getId().'/toggle');

        $new_toggle = $task->isDone();

        $this->assertNotEquals($old_toggle, $new_toggle);

        $this->assertResponseRedirects('/tasks/list', 302, "La redirection après soumission du formulaire a échoué.");
    }

    /**
     * Test : User Delete a task
     * @return void
     */
    public function testUserCanDeleteOneOfTheirTasks(): void 
    {
        $user = $this->createUser();
        $this->client->loginUser($user);

        $task = new Task();
        $task->setTitle('Another task');
        $task->setContent('Another content for a task');
        $task->setAuthor($user);
        $task->setDone(false);
        $task->setCreatedAt(new \DateTime);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $this->client->request('DELETE', 'tasks/'.$task->getId().'/delete');
        $this->assertResponseRedirects('/tasks/list', 302, "La redirection après soumission du formulaire a échoué.");

        $this->client->followRedirect();

        $this->assertSelectorTextContains('.alert-success', "La tâche a bien été supprimée.");
    }

    /**
     * TEST : User cannot delete task from another user
     * @return void
     */
    public function testUserCannotDeleteTaskFromAnotherUser(): void
    {
        $user = $this->createUser();
        $this->client->loginUser($user);

        $task = $this->createTask();

        $this->client->request('DELETE', 'tasks/'.$task->getId().'/delete');
        $this->assertResponseRedirects('/tasks/list', 302, "La redirection après soumission du formulaire a échoué.");

        $this->client->followRedirect();

        $this->assertSelectorTextContains('.alert-danger', "Vous ne pouvez pas supprimer cette tâche.");
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->restoreExceptionHandler();
    }

    protected function restoreExceptionHandler(): void
    {
        while (true) {
            $previousHandler = set_exception_handler(static fn() => null);

            restore_exception_handler();

            if ($previousHandler === null) {
                break;
            }

            restore_exception_handler();
        }
    }
}

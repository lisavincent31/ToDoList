<?php

namespace App\Tests\Controllers;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityControllerTest extends WebTestCase
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
    private function createUser($uniqId): User
    {
        $user = new User();
        $user->setEmail('test'.$uniqId.'@example.com');
        $user->setRoles(['ROLE_USER']);
        $user->setUsername('User_TEST');

        $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    /**
     * Test : Login user successfully
     * @return void
     */
    public function testSuccessfulLogin(): void
    {
        $uniqId = uniqid(3);

        $this->createUser($uniqId);

        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['email'] = 'test'.$uniqId.'@example.com';
        $form['password'] = 'password';
        $this->client->submit($form);

        $this->assertResponseRedirects('/login');
    }

    /**
     * Test : Login user failed
     * @return void
     */
    public function testFailedLogin(): void
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();
        $form['email'] = 'invalid_user@email.com';
        $form['password'] = 'invalid_password';

        $this->client->submit($form);

        $this->assertResponseRedirects('/login');

        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert.alert-danger', 'Invalid credentials.');
    }

    /**
     * Test : A logged in user try to access to /login route
     * Test redirect to homepage
     * @return void
     */
    public function testLoggedInUserTryToAccessToLoginRoute(): void
    {
        $uniqId = uniqid(3);

        $user = $this->createUser($uniqId);

        $this->client->loginUser($user);

        $this->client->request('GET', '/login');
        $this->assertResponseRedirects('/', 302, 'La redirection a échouée.');
    }

    /**
     * Test : Logout a logged in user
     * @return void
     */
    public function testLogoutUser(): void
    {
        $uniqId = uniqid(3);
        // Create and login a user
        $user = $this->createUser($uniqId);
        $this->client->loginUser($user);

        // Request to logout route
        $crawler = $this->client->request('GET', '/logout');

        // Verify the redirection
        $this->assertResponseRedirects('/', 302, 'La redirection a échouée.');

        $this->client->followRedirect();
        $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !');
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
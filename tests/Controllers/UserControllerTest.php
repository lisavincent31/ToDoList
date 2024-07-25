<?php

namespace App\Tests\Controllers;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserControllerTest extends WebTestCase
{
    private $client;
    private $entityManager;
    private $passwordHasher;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->passwordHasher->method('hashPassword')
            ->willReturn('hashed_password');
        
        static::getContainer()->set(UserPasswordHasherInterface::class, $this->passwordHasher);
    }

    private function createAdminUser()
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin@example.com']);

        if (!$user) {
            $user = new User();
            $user->setEmail('admin@example.com');
            $user->setRoles(['ROLE_ADMIN']);
            $user->setUsername('admin');

            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        return $user;
    }

    private function createUser()
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'anonymous@example.com']);

        if (!$user) {
            $user = new User();
            $user->setEmail('anonymous@example.com');
            $user->setRoles(['ROLE_USER']);
            $user->setUsername('Anonymous');

            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        return $user;
    }

    public function testAdminCanAccessToUserList(): void
    {
        $client = $this->client;
        $admin = $this->createAdminUser();

        // Log In the Admin
        $client->loginUser($admin);

        // Request the user list page
        $client->request('GET', '/users');
        $this->assertResponseIsSuccessful("La requête à /users n'a pas réussi.");

        // Sauvegarde du contenu de la réponse pour inspection
        file_put_contents('response.html', $client->getResponse()->getContent());

        // Vérification de l'existence du sélecteur
        $this->assertSelectorExists('h1:contains("Liste des utilisateurs")', "Le sélecteur h1:contains('Liste des utilisateurs') n'a pas été trouvé.");
    }

    /**
     * Test : a role_user cannot access to route user_list
     * @return void
     */
    public function testUserCannotAccessToUserList(): void
    {
        $client = $this->client;
        $user = $this->createUser();

        $client->loginUser($user);

        $client->request('GET', '/users');
        // Ensure the user is redirected to the homepage
        $this->assertResponseRedirects('/', 302, "L'utilisateur n'a pas été redirigé vers la page d'accueil.");

        // Follow the redirect
        $crawler = $client->followRedirect();
 
        // Check the session for the error message
        $this->assertSelectorTextContains('.alert.alert-danger', "Vous n'avez pas les droits suffisants pour accéder à cette page.", "Le message d'erreur n'a pas été trouvé sur la page d'accueil.");
    }

    /**
     * Test : create a new user with role admin
     * @return void
     */
    public function testCreateUserFormIsRendered(): void
    {
        $client = $this->client;
        $crawler = $client->request('GET', 'users/create');

        $this->assertResponseIsSuccessful('Nous ne pouvons pas accéder à la page users/create.');

        $this->assertSelectorExists('form');
    }

    public function testCreateUserFormIsSubmittedByAdmin()
    {
        $client = $this->client;
        $admin = $this->createAdminUser();

        $client->loginUser($admin);

        $crawler = $client->request('POST', 'users/create');

        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'newuser'. uniqid(4),
            'user[email]' => 'newuser'. uniqid(4) .'@example.com',
            'user[password][first]' => 'securepassword',
            'user[password][second]' => 'securepassword',
            'user[roles]' => ['ROLE_USER']
        ]);
        $client->submit($form);

        $this->assertResponseRedirects('/users', 302, "La redirection après soumission du formulaire a échoué.");
        
        $client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', "L'utilisateur a bien été ajouté.");
    }

    public function testCreateUserFormIsSubmittedByUser()
    {
        $client = $this->client;
        $user = $this->createUser();

        $client->loginUser($user);

        $crawler = $client->request('POST', 'users/create');

        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'newuser'. uniqid(4),
            'user[email]' => 'newuser'. uniqid(4) .'@example.com',
            'user[password][first]' => 'securepassword',
            'user[password][second]' => 'securepassword',
            'user[roles]' => ['ROLE_USER']
        ]);
        $client->submit($form);

        $this->assertResponseRedirects('/users', 302, "La redirection après soumission du formulaire a échoué.");
        
        $client->followRedirect();
    }

    public function testEditUserFormIsRendered(): void
    {
        $client = $this->client;

        $user = $this->createUser();
        $client->loginUser($user);

        $crawler = $client->request('GET', 'users/'. $user->getId() .'/edit');

        $this->assertResponseIsSuccessful('Nous ne pouvons pas accéder à la page users/edit.');

        $this->assertSelectorExists('form');
    }

    public function testEditUserFormIsSubmitted(): void
    {
        $client = $this->client;

        $user = $this->createUser();
        $client->loginUser($user);
        
        $crawler = $client->request('POST', 'users/'. $user->getId() .'/edit');

        $form = $crawler->selectButton('Modifier')->form([
            'user[password][first]' => 'securepassword',
            'user[password][second]' => 'securepassword',
            'user[roles]' => ['ROLE_USER']
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/users', 302, "La redirection après soumission du formulaire a échoué.");
        
        $client->followRedirect();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->restoreExceptionHandler();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
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

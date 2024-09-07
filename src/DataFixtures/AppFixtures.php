<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Task;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername("user");
        $user->setEmail("user@example.com");
        $password = $this->passwordHasher->hashPassword($user, "s3cr3t/<:us3r!");
        $user->setPassword($password);
        $user->setRoles(["ROLE_USER"]);
        $manager->persist($user);

        $anonym = new User();
        $anonym->setUsername("Anonymous");
        $anonym->setEmail("anonymous@example.com");
        $password = $this->passwordHasher->hashPassword($user, "s3cr3t/<:an0nymou2!");
        $anonym->setPassword($password);
        $anonym->setRoles(["ROLE_USER"]);
        $manager->persist($anonym);

        $admin = new User();
        $admin->setUsername("Admin");
        $admin->setEmail("admin@example.com");
        $password = $this->passwordHasher->hashPassword($user, "s3cr3t/<:Adm1n!");
        $admin->setPassword($password);
        $admin->setRoles(["ROLE_USER", "ROLE_ADMIN"]);
        $manager->persist($admin);

        for($i=0; $i < 4; $i++) {
            $task = new Task();
            $task->setTitle("User Tâche n°".$i);
            $task->setContent("Ceci est une nouvelle tâche pour User.");
            $task->setAuthor($user);
            $task->setDone(false);
            $task->setCreatedAt(new \Datetime);
            $manager->persist($task);
        }

        for($i=0; $i < 4; $i++) {
            $taskAnonym = new Task();
            $taskAnonym->setTitle("Anonym Tâche n°".$i);
            $taskAnonym->setContent("Ceci est une nouvelle tâche pour Anonyme.");
            $taskAnonym->setAuthor($anonym);
            $taskAnonym->setDone(false);
            $taskAnonym->setCreatedAt(new \Datetime);
            $manager->persist($taskAnonym);
        }

        for($i=0; $i < 4; $i++) {
            $taskAdmin = new Task();
            $taskAdmin->setTitle("Admin Tâche n°".$i);
            $taskAdmin->setContent("Ceci est une nouvelle tâche pour Admin.");
            $taskAdmin->setAuthor($admin);
            $taskAdmin->setDone(false);
            $taskAdmin->setCreatedAt(new \Datetime);
            $manager->persist($taskAdmin);
        }

        $manager->flush();
    }
}

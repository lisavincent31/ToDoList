<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
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
        $user->setUsername("Anonymous");
        $user->setEmail("anonymous@example.com");
        $password = $this->passwordHasher->hashPassword($user, "s3cr3t/<:an0nymou2!");
        $user->setPassword($password);
        $user->setRoles(["ROLE_USER"]);

        $manager->persist($user);

        $manager->flush();
    }
}

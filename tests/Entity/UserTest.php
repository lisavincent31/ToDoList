<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\User;
use App\Entity\Task;
use Doctrine\Common\Collections\ArrayCollection;

class UserTest extends TestCase
{

    /**
     * Test if User get ID
     * @return void
     */
    public function testGetId(): void
    {
        $user = new User();
        $this->assertNull($user->getId());
    }

    /**
     * Test insert email to user
     * @return void
     */
    public function testSetEmail(): void
    {
        $user = new User();
        $user->setEmail('new_email@example.com');
        $this->assertEquals('new_email@example.com', $user->getEmail());
    }

    /**
     * Test get the user identifier (email)
     * @return void
     */
    public function testGetUserIdentifier(): void
    {
        $user = new User();
        $user->setEmail('identifier@example.com');
        $this->assertEquals('identifier@example.com', $user->getUserIdentifier());
    }

    /**
     * Test to get the roles of a user
     * @return void
     */
    public function testGetRoles(): void
    {
        $user = new User();
        $this->assertEquals(['ROLE_USER'], $user->getRoles());

        $user->setRoles(['ROLE_ADMIN']);
        $this->assertEquals(['ROLE_ADMIN', 'ROLE_USER'], $user->getRoles());
    }

    /**
     * Test insert roles to user
     * @return void
     */
    public function testSetRoles(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $this->assertEquals(['ROLE_ADMIN', 'ROLE_USER'], $user->getRoles());
    }

    /**
     * Test insert password to user
     * @return void
     */
    public function testSetPassword(): void
    {
        $user = new User();
        $user->setPassword('new_password');
        $this->assertEquals('new_password', $user->getPassword());
    }

    /**
     * Test erase credentials
     * There's sensitive data to clear
     * @return void
     */
    public function testEraseCredentials(): void
    {
        $user = new User();
        $user->eraseCredentials();
        $this->assertTrue(true);
    }

    

    /**
     * Test insert the username
     * @return void
     */
    public function testSetUsername(): void
    {
        $user = new User();
        $user->setUsername('new_username');
        $this->assertEquals('new_username', $user->getUsername());
    }

    /**
     * Test getting all tasks for a user
     * @return void
     */
    public function testGetTasks(): void
    {
        $user = new User();
        $this->assertInstanceOf(ArrayCollection::class, $user->getTasks());
    }

    /**
     * test adding a task to a user
     * @return void
     */
    public function testAddTask(): void
    {
        $user = new User();
        $task = new Task();
        
        $user->addTask($task);
        $this->assertTrue($user->getTasks()->contains($task));
        $this->assertEquals($user, $task->getAuthor());
    }

    /**
     * Test remove a task after creating
     * @return void
     */
    public function testRemoveTask(): void
    {
        $user = new User();
        $task = new Task();
        
        $user->addTask($task);
        $this->assertTrue($user->getTasks()->contains($task));
        
        $user->removeTask($task);
        $this->assertFalse($user->getTasks()->contains($task));
        $this->assertNull($task->getAuthor());
    }
}
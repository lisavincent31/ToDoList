<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Task;
use App\Entity\User;

class TaskTest extends TestCase
{
    /**
     * Test to get the id of a task
     * @return void
     */
    public function testGetId(): void
    {
        $task = new Task();
        $this->assertNull($task->getId());
    }

    /**
     * Test insert and get title of a task
     * @return void
     */
    public function testSetTitle(): void
    {
        $task = new Task();
        $task->setTitle('This is a task title.');
        $this->assertEquals('This is a task title.', $task->getTitle());
    }

    /**
     * Test insert and get content of a task
     * @return void
     */
    public function testSetContent(): void
    {
        $task = new Task();
        $task->setContent('This is a task content.');
        $this->assertEquals('This is a task content.', $task->getContent());
    }

    /**
     * Test get isDone for a task
     * @return void
     */
    public function testIsDone(): void
    {
        $task = new Task();
        $task->setDone(false);
        $this->assertFalse($task->isDone());
    }

    /**
     * Test the toggle function
     */
    public function testToggle()
    {
        $task = new Task();
        $task->setDone(false);
        $this->assertFalse($task->isDone());

        $task->toggle(true);
        $this->assertTrue($task->isDone(), 'The task should be done (true) after toggle(true).');

        $task->toggle(false);
        $this->assertFalse($task->isDone(), 'The task should be done (false) after toggle(false).');

    }

    /**
     * Test insert and get created_at
     * @return void
     */
    public function testCreatedAt()
    {
        $task = new Task();
        $now = new \DateTime();
        
        $task->setCreatedAt($now);
        $this->assertEquals($now, $task->getCreatedAt());
    }
}

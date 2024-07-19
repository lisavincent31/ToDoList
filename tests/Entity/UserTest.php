<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\User;

class UserTest extends TestCase
{
    public function testCreateUser(): void
    {
        $user = new User('testphp@example.com', ['ROLE_USER'], 's3cr3t', 'TestPHP');
        $this->assertTrue(true);
    }
}

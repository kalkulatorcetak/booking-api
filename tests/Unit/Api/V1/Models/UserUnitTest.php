<?php

namespace Test\Unit\Api\V1\Models;

use App\Api\V1\Models\User;
use Test\UnitTestCase;

class UserUnitTest extends UnitTestCase
{
    /**
     * @test
     */
    public function createUser(): void
    {
        $user = new User();
        $user->name = 'Test User';
        $user->email = 'test@unittest.com';
        $user->setPassword('secret');
        $user->save();

        $this->seeInDatabase('users', ['name' => 'Test User', 'email' => 'test@unittest.com']);
    }

    /**
     * @test
     */
    public function createUserWithRoles(): void
    {
        $user = new User();
        $user->name = 'Test User';
        $user->email = 'test@unittest.com';
        $user->setPassword('secret');
        $user->setRoles(['ADMIN']);
        $user->save();

        $this->seeInDatabase('users', ['name' => 'Test User', 'email' => 'test@unittest.com']);
        $this->assertContains('ADMIN', $user->getRoles());
        $this->assertTrue($user->hasRole('ADMIN'));
        $this->assertTrue($user->isAdmin());
        $this->assertTrue($user->hasPermission('USER_DELETE'));
    }
}

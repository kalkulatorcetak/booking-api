<?php

namespace Test\EndToEnd\Api\V1;

use Test\Helpers\V1\UserTestHelper;
use Test\EndToEndTestCase;

class UserControllerEndToEndTest extends EndToEndTestCase
{
    use UserTestHelper;

    /**
     * @test
     */
    public function listUsersWithValidQueryParameters(): void
    {
        $this->get('/users?filter[]=email:ct:Admin&order[]=name:asc&limit=5&page=1', $this->headers())
            ->seeStatusCode(self::HTTP_OK)
            ->seeJsonStructure($this->userListStructure());
    }

    /**
     * @test
     */
    public function listUsersWithInValidFilterOperator(): void
    {
        $this->get('/users?filter[]=email:like:Admin&order[]=name,asc&limit=5&page=1', $this->headers())
            ->seeStatusCode(self::HTTP_BAD_REQUEST);
    }

    /**
     * @test
     */
    public function listUsersWithInValidQueryParameters(): void
    {
        $this->get('/users?filter[]=email:Admin&order[]=name,asc&limit=5&page=1', $this->headers())
            ->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function getExistingUserById(): void
    {
        $this->get('/users/1', $this->headers())
            ->seeStatusCode(self::HTTP_OK)
            ->seeJsonStructure($this->userStructure());
    }

    /**
     * @test
     */
    public function getNonExistingUserById(): void
    {
        $this->get('/users/99', $this->headers())
            ->seeStatusCode(self::HTTP_NOT_FOUND);
    }

    /**
     * @test
     */
    public function createUserWithValidDatas(): void
    {
        $userData = [
            'name' => 'Create Test',
            'email' => 'test.create@booking-api.dev',
            'password' => 'Secret123',
            'roles' => [
                'CASHIER'
            ],
        ];

        $this->post('/users', $userData, $this->headers())
            ->seeStatusCode(self::HTTP_OK)
            ->seeJsonStructure($this->userStructure());
    }

    /**
     * @test
     */
    public function createUserWithInvalidDatas(): void
    {
        $userData = [
            'name' => 'Create Test',
            'email' => 'test.create@booking-api.dev',
            'password' => '1234',
            'roles' => [
                'CASHIER'
            ],
        ];

        $this->post('/users', $userData, $this->headers())
            ->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)
            ->seeJsonContains(['password' => ['The password must be at least 8 characters.']]);
    }

    /**
     * @test
     */
    public function deleteExistingUser(): void
    {
        $this->delete('/users/51', [], $this->headers())
            ->seeStatusCode(self::HTTP_NO_CONTENT);
    }

    /**
     * @test
     */
    public function deleteNonExistingUser(): void
    {
        $this->delete('/users/99', [], $this->headers())
            ->seeStatusCode(self::HTTP_NOT_FOUND);
    }
}

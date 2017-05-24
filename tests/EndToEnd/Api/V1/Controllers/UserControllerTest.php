<?php

namespace Test\EndToEnd\Api\V1;

use Test\Helpers\UserTestHelper;
use Test\TestCase;

class UserControllerTest extends TestCase
{
    use UserTestHelper;

    /**
     * @test
     */
    public function listUsersWithValidQueryParameters(): void
    {
        $response = $this->client->get('/users?filter[]=email:ct:Admin&order[]=name:asc&limit=5&page=1');

        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());

        $this->seeJsonStructure($this->userListStructure(), $this->getResponseArray($response));
    }

    /**
     * @test
     */
    public function listUsersWithInValidFilterOperator(): void
    {
        $response = $this->client->get('/users?filter[]=email:like:Admin&order[]=name,asc&limit=5&page=1');

        $this->assertEquals(self::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function listUsersWithInValidQueryParameters(): void
    {
        $response = $this->client->get('/users?filter[]=email:Admin&order[]=name,asc&limit=5&page=1');

        $this->assertEquals(self::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function getExistingUserById(): void
    {
        $response = $this->client->get('/users/1');

        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());

        $this->seeJsonStructure($this->userStructure(), $this->getResponseArray($response));
    }

    /**
     * @test
     */
    public function getNonExistingUserById(): void
    {
        $response = $this->client->get('/users/99');

        $this->assertEquals(self::HTTP_NOT_FOUND, $response->getStatusCode());
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

        $response = $this->client->post('/users', ['json' => $userData]);

        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());

        $this->seeJsonStructure($this->userStructure(), $this->getResponseArray($response));
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

        $response = $this->client->post('/users', ['json' => $userData]);

        $this->assertEquals(self::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());

        $expectedError = ['password' => ['The password must be at least 8 characters.']];
        $this->assertArraySubset($expectedError, $this->getResponseErrors($response));
    }

    /**
     * @test
     */
    public function deleteExistingUser(): void
    {
        $response = $this->client->delete('/users/51');

        $this->assertEquals(self::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function deleteNonExistingUser(): void
    {
        $response = $this->client->delete('/users/99');

        $this->assertEquals(self::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}

<?php

namespace Test\EndToEnd\Api\V1;

use Test\TestCase;

class UserControllerTest extends TestCase
{
    /**
     * @test
     */
    public function listUsers()
    {
        $response = $this->client->get('/users?filter[]=email:ct:Admin&order[]=name:asc&limit=5&page=1');

        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());

        $expectedStructure = [
            'data' => [
                '*' => [
                    'type',
                    'id',
                    'attributes' => [
                        'name',
                        'email',
                        'roles',
                        'added',
                        'modified'
                    ]
                ]
            ]
        ];

        $this->seeJsonStructure($expectedStructure, $this->getResponseArray($response));
    }

    /**
     * @test
     */
    public function getExistingUserById()
    {
        $response = $this->client->get('/users/1');

        $this->assertEquals(
            self::HTTP_OK,
            $response->getStatusCode()
        );

        $expectedStructure = [
            'data' => [
                'type',
                'id',
                'attributes' => [
                    'name',
                    'email',
                    'roles',
                    'added',
                    'modified',
                ]
            ]
        ];

        $this->seeJsonStructure($expectedStructure, $this->getResponseArray($response));
    }

    /**
     * @test
     */
    public function getNonExistingUserById()
    {
        $response = $this->client->get('/users/99');

        $this->assertEquals(
            self::HTTP_NOT_FOUND,
            $response->getStatusCode()
        );
    }
}
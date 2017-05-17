<?php

namespace Test\Api\V1;

use Test\TestCase;

class UserControllerTest extends TestCase
{
    /**
     * @test
     */
    public function listUsers()
    {
        $response = $this->client->get('/users?filter[]=email:ct:test&order[]=name:asc&limit=5&page=1');

        $this->assertEquals(
            self::HTTP_OK,
            $response->getStatusCode()
        );

        $expected = [
            'type' => 'string',
            'id' => 'integer',
            'attributes' => [
                'name' => 'string',
                'email' => 'string',
                'roles' => 'array',
                'added' => 'date',
                'modified' => 'date',
            ]
        ];

        $this->assertValidArray($expected, $this->getResponseArray($response)['data'][0]);
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

        $expected = [
            'type' => 'string',
            'id' => 'integer',
            'attributes' => [
                'name' => 'string',
                'email' => 'string',
                'roles' => 'array',
                'added' => 'date',
                'modified' => 'date',
            ]
        ];

        $this->assertValidArray($expected, $this->getResponseArray($response)['data']);
    }

    /**
     * @test
     */
    public function getNonExistingUserById()
    {
        $response = $this->client->get('/users/99999');

        $this->assertEquals(
            self::HTTP_NOT_FOUND,
            $response->getStatusCode()
        );
    }
}
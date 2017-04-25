<?php

namespace Test\Api\V1;

use Test\TestCase;

class UserControllerTest extends TestCase
{
    /**
     * @test
     */
    public function getUserById()
    {
        $response = $this->client->get('/users/1');

        $this->assertEquals(
            self::HTTP_OK,
            $response->getStatusCode()
        );

        $expected = [
            'id' => 'integer',
            'type' => 'string',
            'attributes' => [
                'email' => 'string',
                'name' => 'string',
                'added' => 'date',
            ]
        ];

        $this->assertValidArray($expected, $this->getResponseArray($response)['data']);
    }
}
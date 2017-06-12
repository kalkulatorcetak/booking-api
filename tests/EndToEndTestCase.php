<?php

namespace Test;

use Lukasoppermann\Httpstatus\Httpstatuscodes;

abstract class EndToEndTestCase extends TestCase implements Httpstatuscodes
{
    protected $baseUrl = 'http://test.booking-api.dev';

    protected function headers(): array
    {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vOiIsImlhdCI6MTQ5NzI4MjM0MSwiZXhwIjoxNDk5OTEwMzQxLCJuYmYiOjE0OTcyODIzNDEsImp0aSI6IllKZjlPZ3N0NmRwT3B0OVUiLCJzdWIiOjF9.XeNVK08G-yHGrE0a04uf1pVY8_-q6HMHoVLuE3byOP8';

        return [
            'Accept' => 'application/vnd.booking.v1+json',
            'Authorization' => 'Bearer ' . $token,
            'Cookie' => 'XDEBUG_SESSION=XDEBUG_ECLIPSE',
        ];
    }
}

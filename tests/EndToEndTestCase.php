<?php

namespace Test;

use Lukasoppermann\Httpstatus\Httpstatuscodes;

abstract class EndToEndTestCase extends TestCase implements Httpstatuscodes
{
    protected $baseUrl = 'http://test.booking-api.dev';

    protected function headers(): array
    {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vOiIsImlhdCI6MTQ5NTA4ODg2OSwiZXhwIjoxNDk3NzE2ODY5LCJuYmYiOjE0OTUwODg4NjksImp0aSI6IjVteDJQOXRiMjFhdmFsS0wiLCJzdWIiOjF9.kutMfu0YrojY_JafRPVk1pVNVVddQgDaA1mp1N0GYG8';

        return [
            'Accept' => 'application/vnd.booking.v1+json',
            'Authorization' => 'Bearer ' . $token,
            'Cookie' => 'XDEBUG_SESSION=XDEBUG_ECLIPSE',
        ];
    }
}

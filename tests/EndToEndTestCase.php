<?php

namespace Test;

use Laravel\Lumen\Application;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use Lukasoppermann\Httpstatus\Httpstatuscodes;

abstract class EndToEndTestCase extends BaseTestCase implements Httpstatuscodes
{
    use DatabaseMigrations;

    protected $baseUrl = 'http://test.booking-api.dev';

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);
        $this->artisan('cache:clear');
    }

    protected function headers(): array
    {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vOiIsImlhdCI6MTQ5NTA4ODg2OSwiZXhwIjoxNDk3NzE2ODY5LCJuYmYiOjE0OTUwODg4NjksImp0aSI6IjVteDJQOXRiMjFhdmFsS0wiLCJzdWIiOjF9.kutMfu0YrojY_JafRPVk1pVNVVddQgDaA1mp1N0GYG8';

        return [
            'Accept' => 'application/vnd.booking.v1+json',
            'Authorization' => 'Bearer ' . $token,
            'Cookie' => 'XDEBUG_SESSION=XDEBUG_ECLIPSE',
        ];
    }

    public function createApplication(): Application
    {
        putenv('APP_ENV=testing');
        putenv('CACHE_DRIVER=redis');
        putenv('DB_CONNECTION=testing');
        putenv('API_DOMAIN=test.booking-api.dev');

        return require __DIR__.'/../bootstrap/app.php';
    }
}

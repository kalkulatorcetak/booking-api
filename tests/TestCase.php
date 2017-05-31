<?php

namespace Test;

use Laravel\Lumen\Application;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase  extends BaseTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);
        $this->artisan('cache:clear');
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

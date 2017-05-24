<?php

namespace Test;

use Dingo\Api\Exception\InternalHttpException;
use GuzzleHttp\Client;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use Lukasoppermann\Httpstatus\Httpstatuscodes;
use Psr\Http\Message\ResponseInterface;

abstract class TestCase extends BaseTestCase implements Httpstatuscodes
{
    use DatabaseMigrations;

    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
        parent::setUp();
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vOiIsImlhdCI6MTQ5NTA4ODg2OSwiZXhwIjoxNDk3NzE2ODY5LCJuYmYiOjE0OTUwODg4NjksImp0aSI6IjVteDJQOXRiMjFhdmFsS0wiLCJzdWIiOjF9.kutMfu0YrojY_JafRPVk1pVNVVddQgDaA1mp1N0GYG8';
        $this->baseUrl = env('CI_BUILD_URL', 'http://test.booking-api.dev');
        printf('Base URL: %s', $this->baseUrl);
        $this->client = new Client([
            'base_uri' => env('CI_BUILD_URL', 'http://test.booking-api.dev'),
            'exceptions' => false,
            'headers' => [
                'Accept' => 'application/vnd.booking.v1+json',
                'Authorization' => 'Bearer ' . $token,
                'Cookie' => 'XDEBUG_SESSION=XDEBUG_ECLIPSE',
            ]
        ]);

        $this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);
        $this->artisan('cache:clear');
    }

    protected function getHeaders()
    {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vOiIsImlhdCI6MTQ5NTA4ODg2OSwiZXhwIjoxNDk3NzE2ODY5LCJuYmYiOjE0OTUwODg4NjksImp0aSI6IjVteDJQOXRiMjFhdmFsS0wiLCJzdWIiOjF9.kutMfu0YrojY_JafRPVk1pVNVVddQgDaA1mp1N0GYG8';

        return [
            'Accept' => 'application/vnd.booking.v1+json',
            'Authorization' => 'Bearer ' . $token,
            'Cookie' => 'XDEBUG_SESSION=XDEBUG_ECLIPSE',
        ];
    }

    public function createApplication()
    {
        putenv('APP_ENV=testing');
        putenv('CACHE_DRIVER=redis');
        putenv('DB_CONNECTION=testing');
        putenv('API_DOMAIN=test.booking-api.dev');

        return require __DIR__.'/../bootstrap/app.php';
    }

    public function getResponseArray(ResponseInterface $response): array
    {
        return json_decode($this->getResponseText($response), true);
    }

    public function getResponseText(ResponseInterface $response): string
    {
        return $response->getBody()->getContents();
    }

    public function getResponseErrors(ResponseInterface $response): array
    {
        $responseArray = $this->getResponseArray($response);
        if (!isset($responseArray['error']['errors'])) {
            throw new \InvalidArgumentException("Response doesn't contain errors' array!");
        }

        return $responseArray['error']['errors'];
    }
}

<?php

namespace Test;

use App\Api\V1\Models\User;
use GuzzleHttp\Client;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use Lukasoppermann\Httpstatus\Httpstatuscodes;

abstract class TestCase extends BaseTestCase implements Httpstatuscodes
{
    use TestTrait;

    protected $client;
    protected $jwtAuth;

    public function setUp()
    {
        parent::setUp();
        $this->jwtAuth = app('tymon.jwt.auth');
        $testUser = User::where('email', 'test@booking-api.dev')->first();
        $token = $this->jwtAuth->fromUser($testUser);

        $this->client = new Client([
            'base_uri' => 'http://booking-api.dev',
            'exceptions' => false,
            'headers' => [
                'Accept' => 'application/vnd.booking.v1+json',
                'Authorization' => 'Bearer ' . $token,
            ]
        ]);
    }

    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    public function getResponseArray($response)
    {
        return json_decode($response->getBody()->getContents(), true);
    }
}

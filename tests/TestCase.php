<?php

namespace Test;

use GuzzleHttp\Client;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use Lukasoppermann\Httpstatus\Httpstatuscodes;

abstract class TestCase extends BaseTestCase implements Httpstatuscodes
{
    use TestTrait;

    protected $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = new Client([
            'base_uri' => 'http://booking-api.dev',
            'exceptions' => false,
            'headers' => [
                'Accept' => 'application/vnd.booking.v1+json'
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

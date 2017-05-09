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
                'Accept' => 'application/vnd.booking.v1+json',
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vYm9va2luZy1hcGkuZGV2L2F1dGgvbG9naW4iLCJpYXQiOjE0OTQyMjIwODgsImV4cCI6MTQ5Njg1MDA4OCwibmJmIjoxNDk0MjIyMDg4LCJqdGkiOiJUejBhU2VJakNKSU8zZE51Iiwic3ViIjoxfQ.KTmaDcbxeNVmbBwBPIQOD4fl65UNaLMlo2oksmHFlYA',
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

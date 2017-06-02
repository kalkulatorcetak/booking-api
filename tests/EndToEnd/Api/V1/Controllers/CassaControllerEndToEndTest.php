<?php

namespace Test\EndToEnd\Api\V1\Controllers;

use App\Api\V1\Enums\CassaAccessType;
use App\Api\V1\Enums\Currency;
use Test\EndToEndTestCase;
use Test\Helpers\V1\CassaTestHelper;

class CassaControllerEndToEndTest extends EndToEndTestCase
{
    use CassaTestHelper;

    /**
     * @test
     */
    public function listCassasWithValidQueryParameters(): void
    {
        $this->get('/cassas?filter[]=name:ct:HUF&order[]=name:asc&limit=5&page=1', $this->headers())
            ->seeStatusCode(self::HTTP_OK)
            ->seeJsonStructure($this->cassaListStructure());
    }

    /**
     * @test
     */
    public function listCassasWithInValidFilterOperator(): void
    {
        $this->get('/cassas?filter[]=name:like:HUF&order[]=name,asc&limit=5&page=1', $this->headers())
            ->seeStatusCode(self::HTTP_BAD_REQUEST);
    }

    /**
     * @test
     */
    public function listCassasWithInValidQueryParameters(): void
    {
        $this->get('/cassas?filter[]=name:HUF&order[]=name,asc&limit=5&page=1', $this->headers())
            ->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function getExistingCassaById(): void
    {
        $this->get('/cassas/1', $this->headers())
            ->seeStatusCode(self::HTTP_OK)
            ->seeJsonStructure($this->cassaStructure());
    }

    /**
     * @test
     */
    public function getNonExistingCassaById(): void
    {
        $this->get('/cassas/99', $this->headers())
            ->seeStatusCode(self::HTTP_NOT_FOUND);
    }

    /**
     * @test
     */
    public function createCassaWithValidDatas(): void
    {
        $cassaData = [
            'name' => 'HUF test',
            'currency' => Currency::HUF,
            'users' => [
                [
                    'user_id' => 1,
                    'access_type' => CassaAccessType::EDIT,
                ]
            ],
        ];

        $this->post('/cassas', $cassaData, $this->headers())
            ->seeStatusCode(self::HTTP_OK)
            ->seeJsonStructure($this->cassaStructure());
    }

    /**
     * @test
     */
    public function createCassaWithInvalidDatas(): void
    {
        $cassaData = [
            'name' => '',
            'currency' => Currency::HUF,
            'users' => [],
        ];

        $this->post('/cassas', $cassaData, $this->headers())
            ->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)
            ->seeJsonContains(['name' => ['The name field is required.']]);
    }

    /**
     * @test
     */
    public function deleteExistingCassa(): void
    {
        $this->delete('/cassas/4', [], $this->headers())
            ->seeStatusCode(self::HTTP_NO_CONTENT);
    }

    /**
     * @test
     */
    public function deleteNonExistingCassa(): void
    {
        $this->delete('/cassas/99', [], $this->headers())
            ->seeStatusCode(self::HTTP_NOT_FOUND);
    }
}
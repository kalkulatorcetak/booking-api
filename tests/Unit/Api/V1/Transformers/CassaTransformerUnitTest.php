<?php

namespace Test\Unit\Api\V1\Transformers;

use App\Api\V1\Enums\CassaAccessType;
use App\Api\V1\Enums\Currency;
use App\Api\V1\Transformers\CassaTransformer;
use Carbon\Carbon;
use Test\Helpers\V1\CassaTestHelper;
use Test\UnitTestCase;

class CassaTransformerUnitTest extends UnitTestCase
{
    use CassaTestHelper;

    /**
     * @test
     * @dataProvider cassaDataProvider
     */
    public function cassaTransform($cassaData): void
    {
        $cassa = $this->createCassaStub($cassaData);
        $this->assertEquals($cassaData, (new CassaTransformer())->transform($cassa));
    }

    public function cassaDataProvider(): array
    {
        return [
            [
                [
                    'id' => 1,
                    'name' => 'Test HUF cassa',
                    'currency' => Currency::HUF,
                    'users' => [['id' => 1, 'name' => 'Administrator', 'access_type'=> CassaAccessType::READ]],
                    'added' => (new Carbon())->toDateTimeString(),
                    'modified' => (new Carbon())->toDateTimeString(),
                ]
            ],
            [
                [
                    'id' => 2,
                    'name' => 'Test EUR cassa',
                    'currency' => Currency::EUR,
                    'users' => [['id' => 1, 'name' => 'Administrator', 'access_type'=> CassaAccessType::EDIT]],
                    'added' => (new Carbon())->toDateTimeString(),
                    'modified' => (new Carbon())->toDateTimeString(),
                ]
            ],
            [
                [
                    'id' => 3,
                    'name' => 'Test GBP cassa',
                    'currency' => Currency::GBP,
                    'users' => [['id' => 1, 'name' => 'Administrator', 'access_type'=> CassaAccessType::READ]],
                    'added' => (new Carbon())->toDateTimeString(),
                    'modified' => (new Carbon())->toDateTimeString(),
                ]
            ],
            [
                [
                    'id' => 4,
                    'name' => 'Test USD cassa',
                    'currency' => Currency::USD,
                    'users' => [['id' => 1, 'name' => 'Administrator', 'access_type'=> CassaAccessType::EDIT]],
                    'added' => (new Carbon())->toDateTimeString(),
                    'modified' => (new Carbon())->toDateTimeString(),
                ]
            ],
        ];
    }
}

<?php

namespace Test\Unit\Api\V1\Transformers;

use App\Api\V1\Enums\CassaAccessType;
use App\Api\V1\Enums\Currency;
use App\Api\V1\Models\Cassa;
use App\Api\V1\Models\User;
use App\Api\V1\Transformers\CassaTransformer;
use Carbon\Carbon;
use Test\UnitTestCase;

class CassaTransformerUnitTest extends UnitTestCase
{
    /**
     * @test
     */
    public function cassaTransform(): void
    {
        foreach ($this->cassaDataProvider() as [$cassa, $expected]) {
            $this->assertEquals($expected, (new CassaTransformer())->transform($cassa));
        }
    }

    public function cassaDataProvider(): array
    {
        return [
            [
                $cassa = $this->createCassa('Test HUF Cassa', Currency::HUF, $this->getUsersByIds([1])),
                [
                    'id' => $cassa->id,
                    'name' => $cassa->name,
                    'currency' => $cassa->currency,
                    'users' => [['id' => 1, 'name' => 'Administrator', 'access_type'=> CassaAccessType::EDIT]],
                    'added' => (new Carbon($cassa->created_at))->toDateTimeString(),
                    'modified' => (new Carbon($cassa->updated_at))->toDateTimeString(),
                ]
            ],
            [
                $cassa = $this->createCassa('Test EUR Cassa', Currency::EUR, $this->getUsersByIds([1])),
                [
                    'id' => $cassa->id,
                    'name' => $cassa->name,
                    'currency' => $cassa->currency,
                    'users' => [['id' => 1, 'name' => 'Administrator', 'access_type'=> CassaAccessType::EDIT]],
                    'added' => (new Carbon($cassa->created_at))->toDateTimeString(),
                    'modified' => (new Carbon($cassa->updated_at))->toDateTimeString(),
                ]
            ],
            [
                $cassa = $this->createCassa('Test GBP Cassa', Currency::GBP, $this->getUsersByIds([1])),
                [
                    'id' => $cassa->id,
                    'name' => $cassa->name,
                    'currency' => $cassa->currency,
                    'users' => [['id' => 1, 'name' => 'Administrator', 'access_type'=> CassaAccessType::EDIT]],
                    'added' => (new Carbon($cassa->created_at))->toDateTimeString(),
                    'modified' => (new Carbon($cassa->updated_at))->toDateTimeString(),
                ]
            ],
            [
                $cassa = $this->createCassa('Test USD Cassa', Currency::USD, $this->getUsersByIds([1])),
                [
                    'id' => $cassa->id,
                    'name' => $cassa->name,
                    'currency' => $cassa->currency,
                    'users' => [['id' => 1, 'name' => 'Administrator', 'access_type'=> CassaAccessType::EDIT]],
                    'added' => (new Carbon($cassa->created_at))->toDateTimeString(),
                    'modified' => (new Carbon($cassa->updated_at))->toDateTimeString(),
                ]
            ],
        ];
    }

    protected function createCassa(string $name, string $currency, array $users): Cassa
    {
        $cassa = new Cassa();
        $cassa->name = $name;
        $cassa->currency = $currency;
        $cassa->save();

        foreach ($users as $user) {
            $cassa->addCassaUser($user, new CassaAccessType(CassaAccessType::EDIT));
        }

        return $cassa;
    }

    protected function getUsersByIds(array $userIds): array
    {
        $users = [];

        foreach ($userIds as $userId) {
            $users[] = User::findOrFail($userId);
        }

        return $users;
    }
}

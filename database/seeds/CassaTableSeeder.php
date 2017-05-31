<?php

use App\Api\V1\Enums\CassaAccessType;
use App\Api\V1\Enums\Currency;
use App\Api\V1\Models\Cassa;
use App\Api\V1\Models\User;
use Illuminate\Database\Seeder;

class CassaTableSeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            Currency::HUF,
            Currency::EUR,
            Currency::GBP,
            Currency::USD,
        ];

        Cassa::flushEventListeners();
        foreach ($currencies as $currency) {
            $this->createCassa($currency, $this->getCassaUsers());
        }
    }

    protected function createCassa(string $currency, array $users): void
    {
        $cassa = new Cassa;
        $cassa->name = sprintf('%s cassa', $currency);
        $cassa->currency = $currency;
        $cassa->save();
        foreach ($users as $cassaUser) {
            $cassa->addCassaUser($cassaUser['user'], $cassaUser['access_type']);
        }
    }

    protected function getCassaUsers(): array
    {
        $user = User::findOrFail(1);
        $accessType = new CassaAccessType(CassaAccessType::EDIT);

        return [
            [
                'user' => $user,
                'access_type' => $accessType,
            ],
        ];
    }
}
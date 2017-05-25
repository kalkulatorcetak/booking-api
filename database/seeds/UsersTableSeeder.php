<?php

use App\Api\V1\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        User::flushEventListeners();
        factory(User::class, 50)->create();
    }
}

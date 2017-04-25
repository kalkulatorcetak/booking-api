<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $this->addAdminUser();
        factory('App\Api\V1\Models\User', 50)->create();
    }

    protected function addAdminUser()
    {
        DB::table('users')->insert([
            'name' => 'Test User',
            'email' => 'test@booking-api.dev',
            'password' => app('hash')->make('secret'),
        ]);
    }
}

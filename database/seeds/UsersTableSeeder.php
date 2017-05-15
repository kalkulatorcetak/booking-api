<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $this->addAdminUser();
        factory('App\Api\V1\Models\User', 50)->create();
    }

    protected function addAdminUser()
    {
        app('db')->table('users')->insert([
            'name' => 'Test User',
            'email' => 'test@booking-api.dev',
            'password' => app('hash')->make('secret'),
            'roles' => 'ADMIN',
        ]);
    }
}

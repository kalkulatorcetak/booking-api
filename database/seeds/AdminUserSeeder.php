<?php

use App\Api\V1\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::flushEventListeners();
        $admin = new User;
        $admin->name = 'Administrator';
        $admin->email = 'admin@booking-api.dev';
        $admin->password = app('hash')->make('secret');
        $admin->setRoles(['ADMIN']);
        $admin->save();
    }
}

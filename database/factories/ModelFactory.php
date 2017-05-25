<?php

$factory->define(App\Api\V1\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => app('hash')->make($faker->password(8)),
        'roles' => app('roles')->getRoles()[random_int(0,1)],
    ];
});

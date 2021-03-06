<?php

return [
    'defaults' => [
        'guard' => env('AUTH_GUARD', 'api'),
    ],
    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users'
        ],
    ],
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model'  => \App\Api\V1\Models\User::class,
        ],
    ],
    'passwords' => [
        //
    ],
];
<?php

return [
    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [
        'mysql' => [
            'host' => 'phpmyadmin.dev',
            'port' => '3306',
            'driver' => 'mysql',
            'database'  => 'booking-api',
            'username'  => 'homestead',
            'password'  => 'secret',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
        ],
    ],

    'redis' => [
        'client' => 'predis',
        'default' => [
            'host' => env('REDIS_HOST', 'localhost'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],

    ],
];
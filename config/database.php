<?php

return [
    'default' => env('DB_CONNECTION', 'mysql'),
    'migrations' => 'migrations',
    'connections' => [
        'mysql' => [
            'host' => 'phpmyadmin.dev',
            'port' => '3306',
            'driver' => 'mysql',
            'database'  => env('MYSQL_DATABASE'),
            'username'  => env('MYSQL_USER'),
            'password'  => env('MYSQL_PASSWORD'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
        ],
        'testing' => [
            'driver' => 'sqlite',
            'database'  => ':memory:',
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
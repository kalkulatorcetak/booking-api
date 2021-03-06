<?php

use Dingo\Api\Routing\Router;

$api = app(Router::class);

$api->version(['version' => 'v1', 'namespace' => 'App\Api\V1\Controllers'], function($api) {
    $api->post('/auth/login', 'App\Api\V1\Controllers\AuthController@login');
    $api->get('/auth/refresh', 'App\Api\V1\Controllers\AuthController@refresh');

    $api->group( [ 'middleware' => ['jwt.auth', 'api.throttle'] ], function ($api) {
        $api->get('/users/{id}', 'App\Api\V1\Controllers\UserController@show');
        $api->get('/users', 'App\Api\V1\Controllers\UserController@index');
        $api->post('/users', 'App\Api\V1\Controllers\UserController@store');
        $api->put('/users/{id}', 'App\Api\V1\Controllers\UserController@update');
        $api->delete('/users/{id}', 'App\Api\V1\Controllers\UserController@delete');

        $api->get('/cassas/{id}', 'App\Api\V1\Controllers\CassaController@show');
        $api->get('/cassas', 'App\Api\V1\Controllers\CassaController@index');
        $api->post('/cassas', 'App\Api\V1\Controllers\CassaController@store');
        $api->put('/cassas/{id}', 'App\Api\V1\Controllers\CassaController@update');
        $api->delete('/cassas/{id}', 'App\Api\V1\Controllers\CassaController@delete');

        $api->get('/vouchers/{id}', 'App\Api\V1\Controllers\VoucherController@show');
        $api->get('/vouchers', 'App\Api\V1\Controllers\VoucherController@index');
        $api->post('/vouchers', 'App\Api\V1\Controllers\VoucherController@store');
        $api->put('/vouchers/{id}', 'App\Api\V1\Controllers\VoucherController@update');
        $api->delete('/vouchers/{id}', 'App\Api\V1\Controllers\VoucherController@delete');
    });
});

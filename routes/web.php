<?php

$api = app('Dingo\Api\Routing\Router');

$api->version(['version' => 'v1', 'namespace' => 'App\Api\V1\Controllers'], function($api) {
    $api->post('/auth/login', 'App\Api\V1\Controllers\AuthController@login');
    $api->get('/auth/refresh', 'App\Api\V1\Controllers\AuthController@refresh');

    $api->group( [ 'middleware' => ['jwt.auth', 'api.throttle'] ], function ($api) {
        $api->get('/users/{id}', 'App\Api\V1\Controllers\UserController@show');
        $api->get('/users', 'App\Api\V1\Controllers\UserController@index');
        $api->post('/users', 'App\Api\V1\Controllers\UserController@store');
        $api->put('/users/{id}', 'App\Api\V1\Controllers\UserController@update');
        $api->delete('/users/{id}', 'App\Api\V1\Controllers\UserController@delete');
    });
});

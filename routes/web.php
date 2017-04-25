<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});

$api = app('Dingo\Api\Routing\Router');

$api->version(['version' => 'v1', 'namespace' => 'App\Api\V1\Controllers'], function($api) {
    $api->post('/auth/login', 'App\Api\V1\Controllers\AuthController@login');

    $api->group( [ 'middleware' => ['jwt.auth', 'api.throttle'] ], function ($api) {
        $api->get('/users/{id}', 'App\Api\V1\Controllers\UserController@show');
        $api->get('/users', 'App\Api\V1\Controllers\UserController@index');
    });
});
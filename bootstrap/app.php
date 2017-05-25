<?php

use Tymon\JWTAuth\JWTAuth;
use Dingo\Api\Transformer\Factory;
use Dingo\Api\Http\RateLimit\Handler;
use Dingo\Api\Auth\Auth;

require_once __DIR__.'/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

$app = new Laravel\Lumen\Application(
    dirname(__DIR__) . '/'
);

$app->configure('roles');
$app->configure('permissions');
$app->configure('database');

//$app->withFacades();
$app->withEloquent();

// $app->middleware([
//    App\Http\Middleware\ExampleMiddleware::class
// ]);
$app->routeMiddleware([
    'auth' => App\Http\Middleware\Authenticate::class,
]);

$app->singleton(Illuminate\Contracts\Debug\ExceptionHandler::class, App\Exceptions\Handler::class);
$app->singleton(Illuminate\Contracts\Console\Kernel::class, App\Console\Kernel::class);
$app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\AuthServiceProvider::class);
$app->register(App\Providers\EventServiceProvider::class);
$app->register(Dingo\Api\Provider\LumenServiceProvider::class);
$app->register(Tymon\JWTAuth\Providers\LumenServiceProvider::class);
$app->register(LumenApiQueryParser\Provider\RequestQueryParserProvider::class);
$app->register(App\Providers\PermissionServiceProvider::class);
$app->register(App\Providers\RoleServiceProvider::class);
$app->register(Illuminate\Redis\RedisServiceProvider::class);

$app[Auth::class]->extend('jwt', function ($app) {
    return new Dingo\Api\Auth\Provider\JWT($app[JWTAuth::class]);
});
$app[Handler::class]->extend(function ($app) {
    return new Dingo\Api\Http\RateLimit\Throttle\Authenticated;
});
$app[Factory::class]->setAdapter(function ($app) {
    $fractal = new League\Fractal\Manager;

    $fractal->setSerializer(new League\Fractal\Serializer\JsonApiSerializer);

    return new Dingo\Api\Transformer\Adapter\Fractal($fractal);
});
$app[\Dingo\Api\Exception\Handler::class]->setErrorFormat([
    'error' => [
        'message' => ':message',
        'errors' => ':errors',
        'code' => ':code',
        'status_code' => ':status_code',
        'debug' => ':debug'
    ]
]);

require __DIR__.'/../routes/web.php';

return $app;

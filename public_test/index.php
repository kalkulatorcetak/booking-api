<?php

putenv('APP_ENV=testing');
putenv('DB_CONNECTION=testing');
if (env('CI_BUILD_URL')) {
    putenv('API_DOMAIN=' . env('CI_BUILD_URL'));
} else {
    putenv('API_DOMAIN=test.booking-api.dev');
}
$app = require __DIR__ . '/../bootstrap/app.php';

$app->run();

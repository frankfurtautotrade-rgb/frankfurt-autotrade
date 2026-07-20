<?php

use App\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

$router->get(
    '/login',
    AuthController::class,
    'showLogin'
);

$router->post(
    '/login',
    AuthController::class,
    'login'
);

$router->get(
    '/logout',
    AuthController::class,
    'logout'
);
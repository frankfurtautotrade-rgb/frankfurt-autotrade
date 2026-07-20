<?php

use App\Controllers\AuthController;
use App\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

$router->get(
    '/',
    DashboardController::class,
    'index'
);

/*
|--------------------------------------------------------------------------
| Authentication
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
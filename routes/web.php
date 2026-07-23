<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

$router->get(
    '/login',
    [AuthController::class, 'showLogin'],
    GuestMiddleware::class
);

$router->post(
    '/login',
    [AuthController::class, 'login'],
    GuestMiddleware::class
);

$router->get(
    '/logout',
    [AuthController::class, 'logout'],
    AuthMiddleware::class
);

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

$router->get(
    '/admin/dashboard',
    [DashboardController::class, 'index'],
    AuthMiddleware::class
);
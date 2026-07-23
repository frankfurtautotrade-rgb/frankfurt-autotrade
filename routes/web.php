<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

$router->get('/', [HomeController::class, 'index']);

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

/*
|--------------------------------------------------------------------------
| Development / Testing
|--------------------------------------------------------------------------
*/

$router->get('/test-querybuilder', function () {

    $users = \App\Core\QueryBuilder::table('users')->get();

    echo '<pre>';
    print_r($users);
    echo '</pre>';

});
<?php

use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\VehicleController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

$router->get(
    '/admin',
    DashboardController::class,
    'index'
);

$router->get(
    '/admin/dashboard',
    DashboardController::class,
    'index'
);

$router->get(
    '/admin/vehicles',
    VehicleController::class,
    'index'
);

$router->get(
    '/admin/vehicles/create',
    VehicleController::class,
    'create'
);

$router->post(
    '/admin/vehicles/store',
    VehicleController::class,
    'store'
);
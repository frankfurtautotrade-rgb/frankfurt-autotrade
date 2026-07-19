<?php

use Controllers\Admin\DashboardController;
use Controllers\Admin\LoginController;
use Controllers\Admin\VehicleController;

$router->get(
    '/admin/dashboard',
    DashboardController::class,
    'index'
);

$router->get(
    '/admin/login',
    LoginController::class,
    'index'
);

$router->post(
    '/admin/login',
    LoginController::class,
    'login'
);

$router->get(
    '/admin/logout',
    LoginController::class,
    'logout'
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
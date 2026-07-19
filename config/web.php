<?php

$router->get(
    '/',
    Controllers\Admin\DashboardController::class,
    'index'
);
<?php

declare(strict_types=1);

session_start();

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Core\Application;
use App\Controllers\HomeController;
use App\Controllers\AuthController;

$app = new Application();

$router = $app->router();

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

$router->get('/', [HomeController::class, 'index']);

$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);

$app->run();
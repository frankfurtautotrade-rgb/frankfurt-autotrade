<?php

declare(strict_types=1);

use Core\Router;

require_once __DIR__ . '/../bootstrap.php';

/*
|--------------------------------------------------------------------------
| Load Application Configuration
|--------------------------------------------------------------------------
*/

$app = require __DIR__ . '/../config/app.php';

date_default_timezone_set($app['timezone']);

ini_set('default_charset', $app['charset']);

if ($app['environment'] === 'development' && $app['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '0');
}

/*
|--------------------------------------------------------------------------
| Create Router
|--------------------------------------------------------------------------
*/

$router = new Router();

/*
|--------------------------------------------------------------------------
| Load Routes
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../config/web.php';
require_once __DIR__ . '/../config/admin.php';

/*
|--------------------------------------------------------------------------
| Current Request
|--------------------------------------------------------------------------
*/

$requestMethod = $_SERVER['REQUEST_METHOD'];

$requestUri = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
) ?? '/';

/*
|--------------------------------------------------------------------------
| Base Path
|--------------------------------------------------------------------------
|
| Example:
| http://localhost/frankfurt-autotrade/public/login
|
| Development:
| $basePath = '/frankfurt-autotrade/public';
|
| Production:
| $basePath = '';
|
*/

$basePath = '';

if ($basePath !== '' && str_starts_with($requestUri, $basePath)) {
    $requestUri = substr($requestUri, strlen($basePath));
}

$requestUri = $requestUri === '' ? '/' : '/'.trim($requestUri, '/');

/*
|--------------------------------------------------------------------------
| Dispatch Request
|--------------------------------------------------------------------------
*/

$router->dispatch(
    $requestMethod,
    $requestUri
);
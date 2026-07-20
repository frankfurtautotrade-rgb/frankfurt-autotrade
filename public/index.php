<?php

declare(strict_types=1);

use Core\Router;

require_once __DIR__ . '/../bootstrap.php';

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
| Set:
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

$requestUri = $requestUri === '' ? '/' : $requestUri;

/*
|--------------------------------------------------------------------------
| Dispatch Request
|--------------------------------------------------------------------------
*/

$router->dispatch(
    $requestMethod,
    $requestUri
);
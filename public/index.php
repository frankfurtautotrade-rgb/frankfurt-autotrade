<?php

declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

use Core\Router;

$router = new Router();

require_once __DIR__ . '/../config/web.php';
require_once __DIR__ . '/../config/admin.php';

$requestMethod = $_SERVER['REQUEST_METHOD'];

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove the project folder when running locally.
// Change '/frankfurt-autotrade/public' if your project folder has a different name.
$basePath = '';

if (str_starts_with($requestUri, $basePath)) {
    $requestUri = substr($requestUri, strlen($basePath));
}

if ($requestUri === '') {
    $requestUri = '/';
}

$router->dispatch($requestMethod, $requestUri);
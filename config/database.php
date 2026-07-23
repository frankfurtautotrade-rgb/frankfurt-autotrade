<?php

declare(strict_types=1);

use App\Core\Environment;

return [

    'driver' => Environment::get('DB_CONNECTION', 'mysql'),

    'host' => Environment::get('DB_HOST', '127.0.0.1'),

    'port' => (int) Environment::get('DB_PORT', 3306),

    'database' => Environment::get('DB_DATABASE'),

    'username' => Environment::get('DB_USERNAME'),

    'password' => Environment::get('DB_PASSWORD'),

    'charset' => 'utf8mb4',

];
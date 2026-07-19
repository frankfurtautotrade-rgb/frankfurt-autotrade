<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Start Session
|--------------------------------------------------------------------------
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| Load Configuration
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

/*
|--------------------------------------------------------------------------
| Load Helper Functions
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/app/Helpers/functions.php';

/*
|--------------------------------------------------------------------------
| Autoloader
|--------------------------------------------------------------------------
*/

spl_autoload_register(function (string $class): void {

    $prefixes = [
        'Core\\'        => __DIR__ . '/app/Core/',
        'Controllers\\' => __DIR__ . '/app/Controllers/',
        'Models\\'      => __DIR__ . '/app/Models/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {

        if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
            continue;
        }

        $relativeClass = substr($class, strlen($prefix));

        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (file_exists($file)) {
            require_once $file;
        }

        return;
    }

});
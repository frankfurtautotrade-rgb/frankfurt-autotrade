<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class Middleware
{
    /**
     * Registered middleware.
     */
    private static array $middleware = [];

    /**
     * Register middleware.
     */
    public static function register(string $name, callable $handler): void
    {
        self::$middleware[$name] = $handler;
    }

    /**
     * Execute middleware.
     */
    public static function run(string|array $middleware): void
    {
        $middleware = (array) $middleware;

        foreach ($middleware as $name) {

            if (!isset(self::$middleware[$name])) {
                throw new RuntimeException(
                    "Middleware '{$name}' is not registered."
                );
            }

            call_user_func(self::$middleware[$name]);
        }
    }

    /**
     * Determine whether middleware exists.
     */
    public static function has(string $name): bool
    {
        return isset(self::$middleware[$name]);
    }

    /**
     * Return all registered middleware.
     */
    public static function all(): array
    {
        return self::$middleware;
    }
}
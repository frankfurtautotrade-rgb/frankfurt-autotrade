<?php

declare(strict_types=1);

namespace App\Core;

final class Environment
{
    /**
     * Loaded environment variables.
     */
    private static array $variables = [];

    /**
     * Prevent loading twice.
     */
    private static bool $loaded = false;

    /**
     * Load the .env file.
     */
    public static function load(?string $path = null): void
    {
        if (self::$loaded) {
            return;
        }

        $path ??= dirname(APP_PATH) . DIRECTORY_SEPARATOR . '.env';

        if (!file_exists($path)) {
            self::$loaded = true;
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {

            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (!str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);

            $key = trim($key);
            $value = trim($value);

            $value = trim($value, "\"'");

            self::$variables[$key] = $value;

            $_ENV[$key] = $value;

            $_SERVER[$key] = $value;

            putenv("$key=$value");
        }

        self::$loaded = true;
    }

    /**
     * Get an environment variable.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return self::$variables[$key]
            ?? $_ENV[$key]
            ?? $_SERVER[$key]
            ?? getenv($key)
            ?? $default;
    }

    /**
     * Determine whether a variable exists.
     */
    public static function has(string $key): bool
    {
        return self::get($key) !== null;
    }

    /**
     * Return all loaded variables.
     */
    public static function all(): array
    {
        return self::$variables;
    }
}
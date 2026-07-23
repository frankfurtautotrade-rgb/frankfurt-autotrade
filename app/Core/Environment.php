<?php

declare(strict_types=1);

namespace App\Core;

final class Environment
{
    /**
     * Loaded environment variables.
     *
     * @var array<string, string>
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

        $path ??= dirname(__DIR__, 2)
            . DIRECTORY_SEPARATOR
            . '.env';

        if (!is_file($path)) {
            self::$loaded = true;
            return;
        }

        $lines = file(
            $path,
            FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
        );

        if ($lines === false) {
            self::$loaded = true;
            return;
        }

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

            if ($key === '') {
                continue;
            }

            $value = trim($value, "\"'");

            self::set($key, $value);
        }

        self::$loaded = true;
    }

    /**
     * Reload the environment.
     */
    public static function reload(?string $path = null): void
    {
        self::clear();

        self::load($path);
    }

    /**
     * Set an environment variable.
     */
    public static function set(string $key, string $value): void
    {
        self::$variables[$key] = $value;

        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;

        putenv($key . '=' . $value);
    }

    /**
     * Get an environment variable.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        if (!self::$loaded) {
            self::load();
        }

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
     * Alias of has().
     */
    public static function exists(string $key): bool
    {
        return self::has($key);
    }

    /**
     * Return all loaded variables.
     *
     * @return array<string, string>
     */
    public static function all(): array
    {
        if (!self::$loaded) {
            self::load();
        }

        return self::$variables;
    }

    /**
     * Clear all loaded variables.
     */
    public static function clear(): void
    {
        self::$variables = [];
        self::$loaded = false;
    }

    /**
     * Prevent instantiation.
     */
    private function __construct()
    {
    }

    /**
     * Prevent cloning.
     */
    private function __clone()
    {
    }
}
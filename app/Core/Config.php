<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class Config
{
    /**
     * Loaded configuration cache.
     *
     * @var array<string, array>
     */
    private static array $config = [];

    /**
     * Load a configuration file.
     */
    public static function load(string $file): void
    {
        $file = trim($file);

        if ($file === '') {
            throw new RuntimeException('Configuration file name cannot be empty.');
        }

        $path = dirname(__DIR__, 2)
            . DIRECTORY_SEPARATOR
            . 'config'
            . DIRECTORY_SEPARATOR
            . $file
            . '.php';

        if (!is_file($path)) {
            throw new RuntimeException(
                "Configuration file '{$file}' not found.\nExpected path: {$path}"
            );
        }

        $config = require $path;

        if (!is_array($config)) {
            throw new RuntimeException(
                "Configuration file '{$file}' must return an array."
            );
        }

        self::$config[$file] = $config;
    }

    /**
     * Reload a configuration file.
     */
    public static function reload(string $file): void
    {
        unset(self::$config[$file]);

        self::load($file);
    }

    /**
     * Get a configuration value.
     *
     * Example:
     * Config::get('app.name');
     * Config::get('database.host');
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $key = trim($key);

        if ($key === '') {
            return $default;
        }

        $segments = explode('.', $key);

        $file = array_shift($segments);

        if (!isset(self::$config[$file])) {
            self::load($file);
        }

        $value = self::$config[$file];

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }

    /**
     * Determine whether a configuration value exists.
     */
    public static function has(string $key): bool
    {
        return self::get($key, '__missing__') !== '__missing__';
    }

    /**
     * Alias of has().
     */
    public static function exists(string $key): bool
    {
        return self::has($key);
    }

    /**
     * Set a configuration value at runtime.
     */
    public static function set(string $key, mixed $value): void
    {
        $segments = explode('.', trim($key));

        $file = array_shift($segments);

        if ($file === null || $file === '') {
            throw new RuntimeException('Configuration key cannot be empty.');
        }

        if (!isset(self::$config[$file])) {
            self::load($file);
        }

        $config =& self::$config[$file];

        foreach ($segments as $segment) {
            if (!isset($config[$segment]) || !is_array($config[$segment])) {
                $config[$segment] = [];
            }

            $config =& $config[$segment];
        }

        $config = $value;
    }

    /**
     * Return all loaded configuration.
     *
     * @return array<string, array>
     */
    public static function all(): array
    {
        return self::$config;
    }

    /**
     * Clear the loaded configuration cache.
     */
    public static function clear(): void
    {
        self::$config = [];
    }
}
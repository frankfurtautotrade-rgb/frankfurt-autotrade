<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class Config
{
    /**
     * Cached configuration.
     */
    private static array $config = [];

    /**
     * Load a configuration file.
     */
    public static function load(string $file): void
    {
        $path = APP_PATH . '/../config/' . $file . '.php';

        if (!file_exists($path)) {
            throw new RuntimeException("Configuration file '{$file}' not found.");
        }

        self::$config[$file] = require $path;
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
     * Set a configuration value at runtime.
     */
    public static function set(string $key, mixed $value): void
    {
        $segments = explode('.', $key);

        $file = array_shift($segments);

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
     */
    public static function all(): array
    {
        return self::$config;
    }
}
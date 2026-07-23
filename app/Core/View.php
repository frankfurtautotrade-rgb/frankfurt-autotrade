<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class View
{
    /**
     * Shared variables available in all views.
     *
     * @var array<string, mixed>
     */
    private static array $shared = [];

    /**
     * Share data with every view.
     */
    public static function share(string $key, mixed $value): void
    {
        self::$shared[$key] = $value;
    }

    /**
     * Render a view.
     */
    public static function render(
        string $view,
        array $data = []
    ): void {
        $file = self::resolvePath($view);

        if (!is_file($file)) {
            throw new RuntimeException(
                "View '{$view}' not found.\nExpected path: {$file}"
            );
        }

        extract(
            array_merge(self::$shared, $data),
            EXTR_SKIP
        );

        require $file;
    }

    /**
     * Determine whether a view exists.
     */
    public static function exists(string $view): bool
    {
        return is_file(self::resolvePath($view));
    }

    /**
     * Get the full path to a view.
     */
    public static function path(string $view): string
    {
        return self::resolvePath($view);
    }

    /**
     * Resolve a view name into a file path.
     *
     * Example:
     * auth.login
     * becomes
     * app/Views/auth/login.php
     */
    private static function resolvePath(string $view): string
    {
        return dirname(__DIR__)
            . '/Views/'
            . str_replace('.', DIRECTORY_SEPARATOR, $view)
            . '.php';
    }
}
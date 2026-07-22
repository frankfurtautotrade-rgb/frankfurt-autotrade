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
                "View '{$view}' not found."
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
     * home.index
     * becomes
     * app/Views/home/index.php
     */
    private static function resolvePath(string $view): string
    {
        return APP_PATH
            . '/Views/'
            . str_replace('.', '/', $view)
            . '.php';
    }
}
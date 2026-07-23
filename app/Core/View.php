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
     * Share a variable with every view.
     */
    public static function share(string $key, mixed $value): void
    {
        self::$shared[$key] = $value;
    }

    /**
     * Share multiple variables with every view.
     *
     * @param array<string, mixed> $data
     */
    public static function shareMany(array $data): void
    {
        self::$shared = array_merge(self::$shared, $data);
    }

    /**
     * Return all shared variables.
     *
     * @return array<string, mixed>
     */
    public static function shared(): array
    {
        return self::$shared;
    }

    /**
     * Clear all shared variables.
     */
    public static function clearShared(): void
    {
        self::$shared = [];
    }

    /**
     * Render a view.
     */
    public static function render(
        string $view,
        array $data = []
    ): void {
        $view = self::normalizeView($view);

        if ($view === '') {
            throw new RuntimeException('View name cannot be empty.');
        }

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
        return is_file(
            self::resolvePath(
                self::normalizeView($view)
            )
        );
    }

    /**
     * Return the full path to a view.
     */
    public static function path(string $view): string
    {
        return self::resolvePath(
            self::normalizeView($view)
        );
    }

    /**
     * Normalize a view name.
     */
    private static function normalizeView(string $view): string
    {
        $view = trim($view);

        $view = str_replace(
            ['/', '\\'],
            '.',
            $view
        );

        return trim($view, '.');
    }

    /**
     * Resolve a view name into a file path.
     */
    private static function resolvePath(string $view): string
    {
        return dirname(__DIR__)
            . DIRECTORY_SEPARATOR
            . 'Views'
            . DIRECTORY_SEPARATOR
            . str_replace('.', DIRECTORY_SEPARATOR, $view)
            . '.php';
    }
}
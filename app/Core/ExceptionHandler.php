<?php

declare(strict_types=1);

namespace Core;

use Throwable;

final class ExceptionHandler
{
    public static function register(): void
    {
        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
    }

    public static function handleException(Throwable $exception): void
    {
        Logger::error(
            $exception->getMessage(),
            [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ]
        );

        http_response_code(500);

        if (APP_ENV === 'local') {
            echo '<h1>Unhandled Exception</h1>';
            echo '<pre>';
            echo htmlspecialchars((string) $exception);
            echo '</pre>';
            return;
        }

        require APP_PATH . '/Views/errors/500.php';
    }

    public static function handleError(
        int $severity,
        string $message,
        string $file,
        int $line
    ): bool {
        throw new \ErrorException(
            $message,
            0,
            $severity,
            $file,
            $line
        );
    }
}
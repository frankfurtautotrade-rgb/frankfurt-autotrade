<?php

declare(strict_types=1);

namespace App\Core;

use Throwable;

final class ExceptionHandler
{
    /**
     * Register the global exception handler.
     */
    public static function register(): void
    {
        set_exception_handler([self::class, 'handle']);

        set_error_handler(function (
            int $severity,
            string $message,
            string $file,
            int $line
        ) {
            throw new \ErrorException(
                $message,
                0,
                $severity,
                $file,
                $line
            );
        });
    }

    /**
     * Handle uncaught exceptions.
     */
    public static function handle(Throwable $exception): never
    {
        Logger::error(sprintf(
            "%s in %s:%d\n%s",
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        ));

        $debug = filter_var(
            Environment::get('APP_DEBUG', false),
            FILTER_VALIDATE_BOOLEAN
        );

        http_response_code(500);

        if ($debug) {

            echo "<h1>Unhandled Exception</h1>";

            echo "<pre>";

            echo htmlspecialchars($exception->__toString());

            echo "</pre>";

        } else {

            echo "<h1>500 - Internal Server Error</h1>";

            echo "<p>An unexpected error occurred.</p>";

        }

        exit;
    }
}
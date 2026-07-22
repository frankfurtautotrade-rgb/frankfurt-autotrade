<?php

declare(strict_types=1);

namespace Core;

use JsonException;

final class Response
{
    /**
     * Send an HTTP status code.
     */
    public static function status(int $code): void
    {
        http_response_code($code);
    }

    /**
     * Redirect to another URL.
     */
    public static function redirect(string $url, int $status = 302): never
    {
        if (!headers_sent()) {
            http_response_code($status);
            header("Location: {$url}");
        }

        exit;
    }

    /**
     * Render an error view.
     */
    public static function view(string $view, int $status = 200): never
    {
        self::status($status);

        $file = APP_PATH . '/Views/' . $view . '.php';

        if (file_exists($file)) {
            require $file;
        } else {
            echo "<h1>{$status}</h1>";
        }

        exit;
    }

    /**
     * Return a 404 response.
     */
    public static function notFound(): never
    {
        self::view('errors/404', 404);
    }

    /**
     * Return a 403 response.
     */
    public static function forbidden(): never
    {
        self::view('errors/403', 403);
    }

    /**
     * Return a 500 response.
     */
    public static function serverError(): never
    {
        self::view('errors/500', 500);
    }

    /**
     * Return JSON.
     *
     * @throws JsonException
     */
    public static function json(array $data, int $status = 200): never
    {
        self::status($status);

        header('Content-Type: application/json; charset=utf-8');

        echo json_encode(
            $data,
            JSON_UNESCAPED_UNICODE
            | JSON_UNESCAPED_SLASHES
            | JSON_THROW_ON_ERROR
        );

        exit;
    }

    /**
     * Force file download.
     */
    public static function download(
        string $path,
        ?string $filename = null
    ): never {
        if (!file_exists($path)) {
            self::notFound();
        }

        $filename ??= basename($path);

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($path));

        readfile($path);

        exit;
    }
}
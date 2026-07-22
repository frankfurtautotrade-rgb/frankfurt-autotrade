<?php

declare(strict_types=1);

namespace App\Core;

use JsonException;
use RuntimeException;

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
    public static function redirect(
        string $url,
        int $status = 302
    ): never {
        if (!headers_sent()) {
            http_response_code($status);
            header("Location: {$url}");
        }

        exit;
    }

    /**
     * Redirect back.
     */
    public static function back(): never
    {
        self::redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }

    /**
     * Render a view.
     */
    public static function view(
        string $view,
        array $data = [],
        int $status = 200
    ): never {
        self::status($status);

        View::render($view, $data);

        exit;
    }

    /**
     * Return plain text.
     */
    public static function text(
        string $text,
        int $status = 200
    ): never {
        self::status($status);

        header('Content-Type: text/plain; charset=UTF-8');

        echo $text;

        exit;
    }

    /**
     * Return HTML.
     */
    public static function html(
        string $html,
        int $status = 200
    ): never {
        self::status($status);

        header('Content-Type: text/html; charset=UTF-8');

        echo $html;

        exit;
    }

    /**
     * Return JSON.
     *
     * @throws JsonException
     */
    public static function json(
        array $data,
        int $status = 200
    ): never {
        self::status($status);

        header('Content-Type: application/json; charset=UTF-8');

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
        if (!is_file($path)) {
            throw new RuntimeException("File not found: {$path}");
        }

        $filename ??= basename($path);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($path));

        readfile($path);

        exit;
    }

    /**
     * Return a 404 response.
     */
    public static function notFound(): never
    {
        self::view('errors.404', [], 404);
    }

    /**
     * Return a 403 response.
     */
    public static function forbidden(): never
    {
        self::view('errors.403', [], 403);
    }

    /**
     * Return a 500 response.
     */
    public static function serverError(): never
    {
        self::view('errors.500', [], 500);
    }
}
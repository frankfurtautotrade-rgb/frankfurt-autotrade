<?php

declare(strict_types=1);

namespace App\Core;

use JsonException;
use RuntimeException;

final class Response
{
    public const HTTP_OK = 200;
    public const HTTP_NO_CONTENT = 204;
    public const HTTP_FOUND = 302;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_SERVER_ERROR = 500;

    /**
     * Send an HTTP status code.
     */
    public static function status(int $code): void
    {
        http_response_code($code);
    }

    /**
     * Send an HTTP header.
     */
    public static function header(string $name, string $value): void
    {
        if (!headers_sent()) {
            header($name . ': ' . $value);
        }
    }

    /**
     * Redirect to another URL.
     */
    public static function redirect(
        string $url,
        int $status = self::HTTP_FOUND
    ): never {
        if (!headers_sent()) {
            self::status($status);
            header('Location: ' . $url);
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
        int $status = self::HTTP_OK
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
        int $status = self::HTTP_OK
    ): never {
        self::status($status);

        self::header('Content-Type', 'text/plain; charset=UTF-8');

        echo $text;

        exit;
    }

    /**
     * Return HTML.
     */
    public static function html(
        string $html,
        int $status = self::HTTP_OK
    ): never {
        self::status($status);

        self::header('Content-Type', 'text/html; charset=UTF-8');

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
        int $status = self::HTTP_OK
    ): never {
        self::status($status);

        self::header('Content-Type', 'application/json; charset=UTF-8');

        echo json_encode(
            $data,
            JSON_UNESCAPED_UNICODE
            | JSON_UNESCAPED_SLASHES
            | JSON_THROW_ON_ERROR
        );

        exit;
    }

    /**
     * Return an empty response.
     */
    public static function noContent(): never
    {
        self::status(self::HTTP_NO_CONTENT);

        exit;
    }

    /**
     * Force a file download.
     */
    public static function download(
        string $path,
        ?string $filename = null
    ): never {
        if (!is_file($path)) {
            throw new RuntimeException("File not found: {$path}");
        }

        $filename ??= basename($path);

        $safeFilename = str_replace(
            ['"', "\r", "\n"],
            '',
            $filename
        );

        self::header('Content-Description', 'File Transfer');
        self::header('Content-Type', 'application/octet-stream');
        self::header(
            'Content-Disposition',
            'attachment; filename="' . $safeFilename . '"'
        );
        self::header('Content-Length', (string) filesize($path));

        readfile($path);

        exit;
    }

    /**
     * Display a file in the browser.
     */
    public static function file(
        string $path,
        ?string $contentType = null
    ): never {
        if (!is_file($path)) {
            throw new RuntimeException("File not found: {$path}");
        }

        $contentType ??= mime_content_type($path) ?: 'application/octet-stream';

        self::header('Content-Type', $contentType);
        self::header('Content-Length', (string) filesize($path));

        readfile($path);

        exit;
    }

    /**
     * Return a 404 response.
     */
    public static function notFound(): never
    {
        self::view('errors.404', [], self::HTTP_NOT_FOUND);
    }

    /**
     * Return a 403 response.
     */
    public static function forbidden(): never
    {
        self::view('errors.403', [], self::HTTP_FORBIDDEN);
    }

    /**
     * Return a 500 response.
     */
    public static function serverError(): never
    {
        self::view('errors.500', [], self::HTTP_SERVER_ERROR);
    }
}
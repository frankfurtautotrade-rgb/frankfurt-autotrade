<?php

declare(strict_types=1);

namespace Core;

final class Request
{
    /**
     * Get the HTTP request method.
     */
    public static function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    /**
     * Get the current request URI.
     */
    public static function uri(): string
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

        return is_string($path) ? $path : '/';
    }

    /**
     * Get a GET parameter.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Get a POST parameter.
     */
    public static function post(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Get a request parameter (GET or POST).
     */
    public static function input(string $key, mixed $default = null): mixed
    {
        return $_REQUEST[$key] ?? $default;
    }

    /**
     * Return all request input.
     */
    public static function all(): array
    {
        return array_merge($_GET, $_POST);
    }

    /**
     * Get all GET parameters.
     */
    public static function query(): array
    {
        return $_GET;
    }

    /**
     * Get all POST parameters.
     */
    public static function body(): array
    {
        return $_POST;
    }

    /**
     * Get uploaded file.
     */
    public static function file(string $key): ?array
    {
        return $_FILES[$key] ?? null;
    }

    /**
     * Determine if a request key exists.
     */
    public static function has(string $key): bool
    {
        return array_key_exists($key, $_REQUEST);
    }

    /**
     * Determine if the request is GET.
     */
    public static function isGet(): bool
    {
        return self::method() === 'GET';
    }

    /**
     * Determine if the request is POST.
     */
    public static function isPost(): bool
    {
        return self::method() === 'POST';
    }

    /**
     * Determine if the request is AJAX.
     */
    public static function isAjax(): bool
    {
        return strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
    }

    /**
     * Determine if the request is HTTPS.
     */
    public static function isSecure(): bool
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (int) ($_SERVER['SERVER_PORT'] ?? 80) === 443;
    }

    /**
     * Get client IP.
     */
    public static function ip(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Get user agent.
     */
    public static function userAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    /**
     * Get HTTP referer.
     */
    public static function referer(): ?string
    {
        return $_SERVER['HTTP_REFERER'] ?? null;
    }

    /**
     * Get request host.
     */
    public static function host(): string
    {
        return $_SERVER['HTTP_HOST'] ?? '';
    }

    /**
     * Get request scheme.
     */
    public static function scheme(): string
    {
        return self::isSecure() ? 'https' : 'http';
    }

    /**
     * Get the full current URL.
     */
    public static function fullUrl(): string
    {
        return self::scheme() . '://' . self::host() . ($_SERVER['REQUEST_URI'] ?? '/');
    }
}
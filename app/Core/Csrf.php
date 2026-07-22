<?php

declare(strict_types=1);

namespace Core;

use RuntimeException;

final class Csrf
{
    private const SESSION_KEY = '_csrf_token';

    /**
     * Return the current CSRF token.
     */
    public static function token(): string
    {
        Session::start();

        if (!Session::has(self::SESSION_KEY)) {
            Session::set(self::SESSION_KEY, bin2hex(random_bytes(32)));
        }

        return (string) Session::get(self::SESSION_KEY);
    }

    /**
     * Regenerate the CSRF token.
     */
    public static function regenerate(): void
    {
        Session::set(self::SESSION_KEY, bin2hex(random_bytes(32)));
    }

    /**
     * Validate the submitted CSRF token.
     */
    public static function validate(?string $token = null): bool
    {
        Session::start();

        $sessionToken = Session::get(self::SESSION_KEY);

        $token ??= $_POST['_token'] ?? '';

        if (
            !is_string($sessionToken)
            || $sessionToken === ''
            || !is_string($token)
            || !hash_equals($sessionToken, $token)
        ) {
            return false;
        }

        return true;
    }

    /**
     * Validate or abort with HTTP 419.
     */
    public static function verify(?string $token = null): void
    {
        if (!self::validate($token)) {
            http_response_code(419);

            throw new RuntimeException('Invalid CSRF token.');
        }
    }

    /**
     * Return a hidden HTML input containing the CSRF token.
     */
    public static function field(): string
    {
        return '<input type="hidden" name="_token" value="' .
            htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8') .
            '">';
    }
}
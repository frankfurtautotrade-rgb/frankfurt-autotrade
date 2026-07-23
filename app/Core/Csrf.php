<?php

declare(strict_types=1);

namespace App\Core;

final class Csrf
{
    /**
     * Session key.
     */
    private const SESSION_KEY = '_csrf_token';

    /**
     * Generate a new CSRF token.
     */
    public static function generate(): string
    {
        $token = bin2hex(random_bytes(32));

        Session::set(self::SESSION_KEY, $token);

        return $token;
    }

    /**
     * Get the current CSRF token.
     */
    public static function token(): string
    {
        if (!Session::has(self::SESSION_KEY)) {
            return self::generate();
        }

        return (string) Session::get(self::SESSION_KEY);
    }

    /**
     * Validate a submitted CSRF token.
     */
    public static function validate(?string $token): bool
    {
        if ($token === null || $token === '') {
            return false;
        }

        $sessionToken = Session::get(self::SESSION_KEY);

        if ($sessionToken === null) {
            return false;
        }

        return hash_equals(
            (string) $sessionToken,
            $token
        );
    }

    /**
     * Regenerate the token.
     */
    public static function regenerate(): string
    {
        Session::remove(self::SESSION_KEY);

        return self::generate();
    }
}
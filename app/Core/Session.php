<?php

declare(strict_types=1);

namespace App\Core;

final class Session
{
    /**
     * Session lifetime in seconds.
     */
    private const SESSION_LIFETIME = 7200;

    /**
     * Start the session securely.
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        session_set_cookie_params([
            'lifetime' => self::SESSION_LIFETIME,
            'path'     => '/',
            'domain'   => '',
            'secure'   => self::isHttps(),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        session_start();
    }

    /**
     * Determine whether the session is active.
     */
    public static function isStarted(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * Get the current session ID.
     */
    public static function id(): string
    {
        self::start();

        return session_id();
    }

    /**
     * Get the current session name.
     */
    public static function name(): string
    {
        return session_name();
    }

    /**
     * Store a value.
     */
    public static function set(string $key, mixed $value): void
    {
        self::start();

        $_SESSION[$key] = $value;
    }

    /**
     * Retrieve a value.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        self::start();

        return $_SESSION[$key] ?? $default;
    }

    /**
     * Determine whether a key exists.
     */
    public static function has(string $key): bool
    {
        self::start();

        return array_key_exists($key, $_SESSION);
    }

    /**
     * Remove a key.
     */
    public static function remove(string $key): void
    {
        self::start();

        unset($_SESSION[$key]);
    }

    /**
     * Alias of remove().
     */
    public static function forget(string $key): void
    {
        self::remove($key);
    }

    /**
     * Return all session data.
     */
    public static function all(): array
    {
        self::start();

        return $_SESSION;
    }

    /**
     * Clear all session data.
     */
    public static function clear(): void
    {
        self::start();

        $_SESSION = [];
    }

    /**
     * Alias of clear().
     */
    public static function flush(): void
    {
        self::clear();
    }

    /**
     * Destroy the session completely.
     */
    public static function destroy(): void
    {
        if (!self::isStarted()) {
            return;
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                [
                    'expires'  => time() - 42000,
                    'path'     => $params['path'],
                    'domain'   => $params['domain'],
                    'secure'   => (bool) $params['secure'],
                    'httponly' => (bool) $params['httponly'],
                    'samesite' => 'Lax',
                ]
            );
        }

        session_destroy();
    }

    /**
     * Regenerate the session ID.
     */
    public static function regenerate(bool $deleteOldSession = true): void
    {
        self::start();

        session_regenerate_id($deleteOldSession);
    }

    /**
     * Get and remove a value.
     */
    public static function pull(string $key, mixed $default = null): mixed
    {
        self::start();

        $value = $_SESSION[$key] ?? $default;

        unset($_SESSION[$key]);

        return $value;
    }

    /**
     * Store a flash value.
     */
    public static function flash(string $key, mixed $value): void
    {
        self::set('_flash.' . $key, $value);
    }

    /**
     * Retrieve a flash value.
     */
    public static function getFlash(string $key, mixed $default = null): mixed
    {
        return self::pull('_flash.' . $key, $default);
    }

    /**
     * Append a value to a session array.
     */
    public static function push(string $key, mixed $value): void
    {
        self::start();

        if (!isset($_SESSION[$key]) || !is_array($_SESSION[$key])) {
            $_SESSION[$key] = [];
        }

        $_SESSION[$key][] = $value;
    }

    /**
     * Increment a numeric session value.
     */
    public static function increment(string $key, int $amount = 1): int
    {
        self::start();

        $value = (int) ($_SESSION[$key] ?? 0);
        $value += $amount;

        $_SESSION[$key] = $value;

        return $value;
    }

    /**
     * Decrement a numeric session value.
     */
    public static function decrement(string $key, int $amount = 1): int
    {
        self::start();

        $value = (int) ($_SESSION[$key] ?? 0);
        $value -= $amount;

        $_SESSION[$key] = $value;

        return $value;
    }

    /**
     * Determine whether HTTPS is enabled.
     */
    private static function isHttps(): bool
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (int) ($_SERVER['SERVER_PORT'] ?? 80) === 443;
    }
}
<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\User;

final class Auth
{
    /**
     * Session keys.
     */
    private const SESSION_USER_ID = 'auth.user_id';
    private const SESSION_USER    = 'auth.user';
    private const SESSION_TIME    = 'auth.last_activity';

    /**
     * Remember Me cookie.
     */
    private const REMEMBER_COOKIE = 'remember_token';

    /**
     * Session timeout (30 minutes).
     */
    private const SESSION_TIMEOUT = 1800;

    /**
     * User model.
     */
    private static ?User $model = null;

    /**
     * Return the User model instance.
     */
    private static function model(): User
    {
        if (self::$model === null) {
            self::$model = new User();
        }

        return self::$model;
    }

    /**
     * Attempt to authenticate a user.
     */
    public static function attempt(
        string $email,
        string $password,
        bool $remember = false
    ): bool {

        $user = self::model()->findByEmail($email);

        if ($user === null) {
            return false;
        }

        if (!(bool) $user['is_active']) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        self::login($user, $remember);

        return true;
    }

    /**
     * Log a user into the application.
     */
    public static function login(
        array $user,
        bool $remember = false
    ): void {

        Session::regenerate();

        Session::set(
            self::SESSION_USER_ID,
            (int) $user['id']
        );

        Session::set(
            self::SESSION_USER,
            [
                'id'         => (int) $user['id'],
                'name'       => $user['name'],
                'email'      => $user['email'],
                'role'       => $user['role'],
                'is_active'  => (bool) $user['is_active'],
            ]
        );

        Session::set(
            self::SESSION_TIME,
            time()
        );

        self::model()->updateLastLogin(
            (int) $user['id']
        );

        if ($remember) {
            self::remember(
                (int) $user['id']
            );
        }
    }

    /**
     * Determine whether the user is authenticated.
     */
    public static function check(): bool
    {
        self::checkTimeout();

        if (Session::has(self::SESSION_USER_ID)) {

            Session::set(
                self::SESSION_TIME,
                time()
            );

            return true;
        }

        return self::loginFromRememberCookie();
    }

    /**
     * Determine whether the visitor is a guest.
     */
    public static function guest(): bool
    {
        return !self::check();
    }

    /**
     * Return the authenticated user's ID.
     */
    public static function id(): ?int
    {
        return Session::get(
            self::SESSION_USER_ID
        );
    }

        /**
     * Return the authenticated user.
     */
    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }

        return Session::get(self::SESSION_USER);
    }

    /**
     * Return the authenticated user's role.
     */
    public static function role(): ?string
    {
        return self::user()['role'] ?? null;
    }

    /**
     * Determine whether the authenticated user is an administrator.
     */
    public static function isAdmin(): bool
    {
        return self::role() === 'admin';
    }

    /**
     * Determine whether the authenticated user has the given role.
     */
    public static function hasRole(string $role): bool
    {
        return self::role() === $role;
    }

    /**
     * Require authentication.
     */
    public static function requireLogin(): void
    {
        if (self::guest()) {
            Response::redirect('/login');
            exit;
        }
    }

    /**
     * Log out the current user.
     */
    public static function logout(): void
    {
        $user = self::id();

        if ($user !== null) {
            self::model()->updateRememberToken($user, null);
        }

        setcookie(
            self::REMEMBER_COOKIE,
            '',
            [
                'expires'  => time() - 3600,
                'path'     => '/',
                'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
                'httponly' => true,
                'samesite' => 'Lax',
            ]
        );

        Session::remove(self::SESSION_USER_ID);
        Session::remove(self::SESSION_USER);
        Session::remove(self::SESSION_TIME);

        Session::regenerate();
    }

    /**
     * Store a persistent login token.
     */
    private static function remember(int $userId): void
    {
        $token = bin2hex(random_bytes(32));

        self::model()->updateRememberToken(
            $userId,
            $token
        );

        setcookie(
            self::REMEMBER_COOKIE,
            $token,
            [
                'expires'  => time() + (60 * 60 * 24 * 30),
                'path'     => '/',
                'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
                'httponly' => true,
                'samesite' => 'Lax',
            ]
        );
    }

    /**
     * Attempt automatic login using the remember-me cookie.
     */
    private static function loginFromRememberCookie(): bool
    {
        if (!isset($_COOKIE[self::REMEMBER_COOKIE])) {
            return false;
        }

        $user = self::model()->findByRememberToken(
            $_COOKIE[self::REMEMBER_COOKIE]
        );

        if ($user === null) {
            return false;
        }

        if (!(bool) $user['is_active']) {
            return false;
        }

        self::login($user);

        return true;
    }

    /**
     * Destroy expired sessions.
     */
    private static function checkTimeout(): void
    {
        if (!Session::has(self::SESSION_TIME)) {
            return;
        }

        $lastActivity = (int) Session::get(
            self::SESSION_TIME
        );

        if ((time() - $lastActivity) > self::SESSION_TIMEOUT) {
            self::logout();
        }
    }
}
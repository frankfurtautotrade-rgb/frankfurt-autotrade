<?php

declare(strict_types=1);

namespace Core;

use App\Models\User;

final class Auth
{
    /**
     * Attempt to authenticate a user.
     */
    public static function attempt(string $email, string $password): bool
    {
        Session::start();

        $userModel = new User();

        $user = $userModel->findByEmail($email);

        if (!$user) {
            return false;
        }

        if (!(bool) $user['is_active']) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        session_regenerate_id(true);

        Session::set('user_id', (int) $user['id']);
        Session::set('role_id', (int) $user['role_id']);
        Session::set('user_name', trim($user['first_name'] . ' ' . $user['last_name']));
        Session::set('language', $user['language'] ?? 'de');

        $userModel->updateLastLogin((int) $user['id']);

        return true;
    }

    /**
     * Log out the current user.
     */
    public static function logout(): void
    {
        Session::start();

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                (bool) $params['secure'],
                (bool) $params['httponly']
            );
        }

        session_destroy();
    }

    /**
     * Check whether a user is authenticated.
     */
    public static function check(): bool
    {
        Session::start();

        return Session::has('user_id');
    }

    /**
     * Return the current user's ID.
     */
    public static function id(): ?int
    {
        Session::start();

        return Session::has('user_id')
            ? (int) Session::get('user_id')
            : null;
    }

    /**
     * Return the current user's role ID.
     */
    public static function roleId(): ?int
    {
        Session::start();

        return Session::has('role_id')
            ? (int) Session::get('role_id')
            : null;
    }

    /**
     * Return the current user's full name.
     */
    public static function userName(): ?string
    {
        Session::start();

        return Session::has('user_name')
            ? (string) Session::get('user_name')
            : null;
    }

    /**
     * Return the current user's language.
     */
    public static function language(): string
    {
        Session::start();

        return Session::has('language')
            ? (string) Session::get('language')
            : 'de';
    }

    /**
     * Require authentication.
     */
    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: /login');
            exit;
        }
    }
}
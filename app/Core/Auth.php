<?php

namespace Core;

use App\Models\User;

class Auth
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

        Session::set('user_id', (int) $user['id']);
        Session::set('role_id', (int) $user['role_id']);
        Session::set('user_name', trim($user['first_name'] . ' ' . $user['last_name']));
        Session::set('language', $user['language']);

        $userModel->updateLastLogin((int) $user['id']);

        session_regenerate_id(true);

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
                $params['secure'],
                $params['httponly']
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
     * Return the current user's name.
     */
    public static function userName(): ?string
    {
        Session::start();

        return Session::has('user_name')
            ? Session::get('user_name')
            : null;
    }

    /**
     * Return the current user's language.
     */
    public static function language(): string
    {
        Session::start();

        return Session::has('language')
            ? Session::get('language')
            : 'de';
    }
}
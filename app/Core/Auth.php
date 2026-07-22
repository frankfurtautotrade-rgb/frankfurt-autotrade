<?php

declare(strict_types=1);

namespace App\Core;

use App\Database\Database;
use PDO;

final class Auth
{
    /**
     * Session key used for the logged-in user.
     */
    private const SESSION_KEY = 'user_id';

    /**
     * Attempt to authenticate a user.
     */
    public static function attempt(string $email, string $password): bool
    {
        /** @var PDO $db */
        $db = Database::connection();

        $stmt = $db->prepare("
            SELECT *
            FROM users
            WHERE email = :email
            LIMIT 1
        ");

        $stmt->execute([
            'email' => $email,
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        Session::regenerate();

        Session::set(self::SESSION_KEY, (int) $user['id']);

        return true;
    }

    /**
     * Determine whether the user is authenticated.
     */
    public static function check(): bool
    {
        return Session::has(self::SESSION_KEY);
    }

    /**
     * Determine whether the user is a guest.
     */
    public static function guest(): bool
    {
        return !self::check();
    }

    /**
     * Return the logged-in user.
     */
    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }

        /** @var PDO $db */
        $db = Database::connection();

        $stmt = $db->prepare("
            SELECT *
            FROM users
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => Session::get(self::SESSION_KEY),
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    /**
     * Return the logged-in user's ID.
     */
    public static function id(): ?int
    {
        return Session::get(self::SESSION_KEY);
    }

    /**
     * Log the current user out.
     */
    public static function logout(): void
    {
        Session::forget(self::SESSION_KEY);

        Session::regenerate();
    }

    /**
     * Require authentication.
     */
    public static function requireLogin(): void
    {
        if (self::guest()) {
            Response::redirect('/login');
        }
    }
}
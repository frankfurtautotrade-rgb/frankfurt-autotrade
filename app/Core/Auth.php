<?php

namespace Core;

class Auth
{
    public static function check(): bool
    {
        return Session::has('admin_id');
    }

    public static function id(): ?int
    {
        return Session::get('admin_id');
    }

    public static function login(int $adminId): void
    {
        Session::set('admin_id', $adminId);
    }

    public static function logout(): void
    {
        Session::destroy();
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: /admin/login');
            exit;
        }
    }
}
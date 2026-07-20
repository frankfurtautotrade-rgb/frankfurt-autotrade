<?php

namespace App\Controllers;

class AuthController
{
    /**
     * Display login page.
     */
    public function showLogin(): void
    {
        require APP_PATH . '/Views/auth/login.php';
    }

    /**
     * Process login.
     */
    public function login(): void
    {
        // Will be implemented later.
    }

    /**
     * Logout user.
     */
    public function logout(): void
    {
        // Will be implemented later.
    }
}
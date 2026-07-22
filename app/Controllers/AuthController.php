<?php

namespace App\Controllers;

use Core\Auth;
use Core\Session;

class AuthController
{
    /**
     * Display login page.
     */
    public function showLogin(): void
    {
        Session::start();

        if (!Session::has('_token')) {
            Session::set('_token', bin2hex(random_bytes(32)));
        }

        $error = null;
        $email = '';

        require APP_PATH . '/Views/auth/login.php';
    }

    /**
     * Handle login request.
     */
    public function login(): void
    {
        Session::start();

        // Validate CSRF token
        $token = $_POST['_token'] ?? '';

        if (
            !Session::has('_token') ||
            !hash_equals(Session::get('_token'), $token)
        ) {
            http_response_code(419);

            $error = 'Die Sitzung ist abgelaufen. Bitte versuchen Sie es erneut.';
            $email = '';

            require APP_PATH . '/Views/auth/login.php';
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {

            $error = 'Bitte E-Mail und Passwort eingeben.';

            require APP_PATH . '/Views/auth/login.php';
            return;
        }

        if (!Auth::attempt($email, $password)) {

            $error = 'Ungültige E-Mail oder Passwort.';

            require APP_PATH . '/Views/auth/login.php';
            return;
        }

        // Prevent session fixation
        session_regenerate_id(true);

        // Generate a fresh CSRF token after login
        Session::set('_token', bin2hex(random_bytes(32)));

        header('Location: /admin');
        exit;
    }

    /**
     * Logout user.
     */
    public function logout(): void
    {
        Session::start();

        Auth::logout();

        header('Location: /login');
        exit;
    }
}
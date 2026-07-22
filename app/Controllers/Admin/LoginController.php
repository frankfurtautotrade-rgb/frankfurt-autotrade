<?php

namespace App\Controllers\Admin;

use Core\Auth;
use Core\Controller;
use Core\Session;

class LoginController extends Controller
{
    /**
     * Display login page.
     */
    public function index(): void
    {
        Session::start();

        if (!Session::has('_token')) {
            Session::set('_token', bin2hex(random_bytes(32)));
        }

        $this->view('auth/login');
    }

    /**
     * Process login.
     */
    public function login(): void
    {
        Session::start();

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {

            $error = 'Bitte E-Mail und Passwort eingeben.';

            $this->view('auth/login', compact('error'));

            return;
        }

        if (!Auth::attempt($email, $password)) {

            $error = 'Ungültige E-Mail oder Passwort.';

            $this->view('auth/login', compact('error'));

            return;
        }

        header('Location: /admin');
        exit;
    }

    /**
     * Logout.
     */
    public function logout(): void
    {
        Auth::logout();

        header('Location: /login');
        exit;
    }
}
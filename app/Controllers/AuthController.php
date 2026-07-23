<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

final class AuthController
{
    /**
     * Display the login page.
     */
    public function showLogin(): never
    {
        if (Auth::check()) {
            Response::redirect('/admin/dashboard');
        }

        Response::view('auth.login', [
            'csrf'    => Csrf::token(),
            'error'   => Session::getFlash('error'),
            'success' => Session::getFlash('success'),
            'email'   => Session::getFlash('old_email'),
        ]);
    }

    /**
     * Process the login request.
     */
    public function login(): never
    {
        if (!Request::isPost()) {
            Response::redirect('/login');
        }

        // Validate CSRF token
        if (!Csrf::validate(Request::post('_token'))) {
            Session::flash('error', 'Invalid security token.');
            Response::redirect('/login');
        }

        $email = trim((string) Request::post('email', ''));
        $password = (string) Request::post('password', '');
        $remember = Request::has('remember');

        if ($email === '' || $password === '') {
            Session::flash('error', 'Please enter your email address and password.');
            Session::flash('old_email', $email);

            Response::redirect('/login');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Please enter a valid email address.');
            Session::flash('old_email', $email);

            Response::redirect('/login');
        }

        if (!Auth::attempt($email, $password, $remember)) {
            Session::flash('error', 'Invalid email or password.');
            Session::flash('old_email', $email);

            Response::redirect('/login');
        }

        Session::flash('success', 'Welcome back!');

        Response::redirect('/admin/dashboard');
    }

    /**
     * Log the current user out.
     */
    public function logout(): never
    {
        Auth::logout();

        Session::flash('success', 'You have been logged out successfully.');

        Response::redirect('/login');
    }
}
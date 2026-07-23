<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Response;
use App\Core\Session;

final class AuthMiddleware
{
    /**
     * Require the user to be authenticated.
     */
    public static function handle(): void
    {
        if (!Auth::check()) {
            Session::flash('error', 'Please log in to continue.');

            Response::redirect('/login');
        }

        // Optional: Check for session timeout if your Auth class supports it.
        if (method_exists(Auth::class, 'checkTimeout')) {
            Auth::checkTimeout();
        }
    }
}
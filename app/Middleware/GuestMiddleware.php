<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Response;

final class GuestMiddleware
{
    /**
     * Allow access only to guests.
     * Logged-in users are redirected to the dashboard.
     */
    public static function handle(): void
    {
        if (Auth::check()) {
            Response::redirect('/admin/dashboard');
        }
    }
}
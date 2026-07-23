<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Response;
use App\Core\View;

final class DashboardController
{
    /**
     * Display the admin dashboard.
     */
    public function index(): never
    {
        Response::view('admin.dashboard', [
            'user' => Auth::user(),
        ]);
    }
}
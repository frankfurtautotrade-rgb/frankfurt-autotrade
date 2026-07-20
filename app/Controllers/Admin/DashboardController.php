<?php

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Session;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): void
    {
        Session::start();

        // User is not logged in
        if (!Session::has('user_id')) {
            header('Location: /login');
            exit;
        }

        // Load dashboard view
        $this->view('admin/dashboard/index');
    }
}
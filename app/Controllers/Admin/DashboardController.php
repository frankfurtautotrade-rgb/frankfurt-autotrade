<?php

namespace Controllers\Admin;

use Core\Controller;
use Core\Session;

class DashboardController extends Controller
{
    public function index(): void
    {
        Session::start();

        if (!Session::has('user_id')) {
            header('Location: /admin/login');
            exit;
        }

        $this->view('admin/dashboard/index');
    }
}
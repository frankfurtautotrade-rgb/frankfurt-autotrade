<?php

namespace Controllers\Admin;

use Core\Controller;
use Core\Session;
use Models\Vehicle;

class VehicleController extends Controller
{
    public function index(): void
    {
        Session::start();

        if (!Session::has('user_id')) {
            header('Location: /admin/login');
            exit;
        }

        $vehicle = new Vehicle();

        $vehicles = $vehicle->all();

        $this->view('admin/vehicles/index', [
            'vehicles' => $vehicles
        ]);
    }

    public function create(): void
    {
        Session::start();

        if (!Session::has('user_id')) {
            header('Location: /admin/login');
            exit;
        }

        $this->view('admin/vehicles/create');
    }

    public function store(): void
    {
        Session::start();

        if (!Session::has('user_id')) {
            header('Location: /admin/login');
            exit;
        }

        $vehicle = new Vehicle();

        $vehicle->create($_POST);

        header('Location: /admin/vehicles');
        exit;
    }
}
<?php

namespace App\Controllers\Admin;

use App\Models\Vehicle;
use Core\Controller;
use Core\Session;

class VehicleController extends Controller
{
    /**
     * Display all vehicles.
     */
    public function index(): void
    {
        Session::start();

        if (!Session::has('user_id')) {
            header('Location: /login');
            exit;
        }

        $vehicle = new Vehicle();

        $vehicles = $vehicle->all();

        $this->view('admin/vehicles/index', [
            'title'    => 'Vehicles',
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * Display create vehicle form.
     */
    public function create(): void
    {
        Session::start();

        if (!Session::has('user_id')) {
            header('Location: /login');
            exit;
        }

        $this->view('admin/vehicles/create', [
            'title' => 'Add Vehicle',
        ]);
    }

    /**
     * Store a new vehicle.
     */
    public function store(): void
    {
        Session::start();

        if (!Session::has('user_id')) {
            header('Location: /login');
            exit;
        }

        $vehicle = new Vehicle();

        $vehicle->create($_POST);

        header('Location: /admin/vehicles');
        exit;
    }
}
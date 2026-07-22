<?php

declare(strict_types=1);

namespace App\Controllers;

class HomeController
{
    /**
     * Display the home page.
     */
    public function index(): void
    {
        require_once dirname(__DIR__) . '/Views/home/index.php';
    }
}
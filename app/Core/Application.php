<?php

declare(strict_types=1);

namespace App\Core;

use App\Controllers\Router;

class Application
{
    /**
     * Application router instance.
     */
    private Router $router;

    /**
     * Create a new application instance.
     */
    public function __construct()
    {
        $this->router = new Router();
    }

    /**
     * Get the router instance.
     */
    public function router(): Router
    {
        return $this->router;
    }

    /**
     * Run the application.
     */
    public function run(): void
    {
        $this->router->dispatch(
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI']
        );
    }
}
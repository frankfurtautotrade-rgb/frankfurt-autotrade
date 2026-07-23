<?php

declare(strict_types=1);

namespace App\Controllers;

class Router
{
    /**
     * Registered routes.
     *
     * @var array<string, array<string, array>>
     */
    private array $routes = [];

    /**
     * Register a GET route.
     */
    public function get(
        string $uri,
        callable|array $action,
        ?string $middleware = null
    ): void {
        $this->addRoute('GET', $uri, $action, $middleware);
    }

    /**
     * Register a POST route.
     */
    public function post(
        string $uri,
        callable|array $action,
        ?string $middleware = null
    ): void {
        $this->addRoute('POST', $uri, $action, $middleware);
    }

    /**
     * Register a route.
     */
    private function addRoute(
        string $method,
        string $uri,
        callable|array $action,
        ?string $middleware
    ): void {
        $uri = $this->normalizeUri($uri);

        $this->routes[$method][$uri] = [
            'action' => $action,
            'middleware' => $middleware,
        ];
    }

    /**
     * Dispatch the current request.
     */
    public function dispatch(string $method, string $uri): void
    {
        $uri = $this->normalizeUri($uri);

        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            exit('404 - Page Not Found');
        }

        $route = $this->routes[$method][$uri];

        /*
        |--------------------------------------------------------------------------
        | Execute middleware
        |--------------------------------------------------------------------------
        */

        if (!empty($route['middleware'])) {

            $middleware = $route['middleware'];

            if (!class_exists($middleware)) {
                throw new \Exception(
                    "Middleware {$middleware} does not exist."
                );
            }

            if (!method_exists($middleware, 'handle')) {
                throw new \Exception(
                    "Middleware {$middleware} must contain handle()."
                );
            }

            $middleware::handle();
        }

        /*
        |--------------------------------------------------------------------------
        | Execute action
        |--------------------------------------------------------------------------
        */

        $action = $route['action'];

        // Closure

        if (is_callable($action)) {
            call_user_func($action);
            return;
        }

        // Controller

        [$controllerClass, $controllerMethod] = $action;

        $controller = new $controllerClass();

        if (!method_exists($controller, $controllerMethod)) {
            throw new \Exception(
                "Method {$controllerMethod} not found in {$controllerClass}"
            );
        }

        $controller->$controllerMethod();
    }

    /**
     * Normalize URI.
     */
    private function normalizeUri(string $uri): string
    {
        $uri = parse_url($uri, PHP_URL_PATH);

        if ($uri === null || $uri === false) {
            return '/';
        }

        $uri = '/' . trim($uri, '/');

        return $uri === '//' ? '/' : $uri;
    }
}
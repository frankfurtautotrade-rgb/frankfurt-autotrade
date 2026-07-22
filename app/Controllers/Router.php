<?php

declare(strict_types=1);

namespace App\Controllers;

class Router
{
    /**
     * Registered routes.
     *
     * @var array<string, array<string, callable|array>>
     */
    private array $routes = [];

    /**
     * Register a GET route.
     */
    public function get(string $uri, callable|array $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }

    /**
     * Register a POST route.
     */
    public function post(string $uri, callable|array $action): void
    {
        $this->addRoute('POST', $uri, $action);
    }

    /**
     * Register any route.
     */
    private function addRoute(string $method, string $uri, callable|array $action): void
    {
        $uri = $this->normalizeUri($uri);

        $this->routes[$method][$uri] = $action;
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

        $action = $this->routes[$method][$uri];

        // Anonymous function
        if (is_callable($action)) {
            call_user_func($action);
            return;
        }

        // [Controller::class, 'method']
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
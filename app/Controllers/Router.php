<?php

declare(strict_types=1);

namespace App\Controllers;

use RuntimeException;

final class Router
{
    /**
     * Registered routes.
     *
     * @var array<string, array<string, array{
     *     action: callable|array{0: class-string, 1: string},
     *     middleware: ?class-string
     * }>>
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
        $method = strtoupper($method);
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
        $method = strtoupper($method);
        $uri = $this->normalizeUri($uri);

        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            exit('404 - Page Not Found');
        }

        $route = $this->routes[$method][$uri];

        $this->executeMiddleware($route['middleware']);

        $this->executeAction($route['action']);
    }

    /**
     * Execute route middleware.
     */
    private function executeMiddleware(?string $middleware): void
    {
        if ($middleware === null) {
            return;
        }

        if (!class_exists($middleware)) {
            throw new RuntimeException(
                "Middleware '{$middleware}' does not exist."
            );
        }

        if (!method_exists($middleware, 'handle')) {
            throw new RuntimeException(
                "Middleware '{$middleware}' must implement handle()."
            );
        }

        $middleware::handle();
    }

    /**
     * Execute route action.
     *
     * @param callable|array{0: class-string, 1: string} $action
     */
    private function executeAction(callable|array $action): void
    {
        if (is_callable($action)) {
            $action();
            return;
        }

        if (count($action) !== 2) {
            throw new RuntimeException(
                'Invalid controller action definition.'
            );
        }

        [$controllerClass, $controllerMethod] = $action;

        if (!class_exists($controllerClass)) {
            throw new RuntimeException(
                "Controller '{$controllerClass}' does not exist."
            );
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $controllerMethod)) {
            throw new RuntimeException(
                "Method '{$controllerMethod}' does not exist in '{$controllerClass}'."
            );
        }

        $controller->$controllerMethod();
    }

    /**
     * Normalize URI.
     */
    private function normalizeUri(string $uri): string
    {
        $path = parse_url($uri, PHP_URL_PATH);

        if ($path === null || $path === false || $path === '') {
            return '/';
        }

        $path = '/' . trim($path, '/');

        return $path === '//' ? '/' : $path;
    }

    /**
     * Determine whether a route exists.
     */
    public function has(string $method, string $uri): bool
    {
        $method = strtoupper($method);
        $uri = $this->normalizeUri($uri);

        return isset($this->routes[$method][$uri]);
    }

    /**
     * Return all registered routes.
     *
     * @return array<string, array<string, array>>
     */
    public function routes(): array
    {
        return $this->routes;
    }
}
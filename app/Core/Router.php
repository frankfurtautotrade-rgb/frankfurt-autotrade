<?php

declare(strict_types=1);

namespace Core;

use RuntimeException;

final class Router
{
    /**
     * Registered routes.
     */
    private array $routes = [];

    /**
     * Register a GET route.
     */
    public function get(string $uri, string $controller, string $method): void
    {
        $this->addRoute('GET', $uri, $controller, $method);
    }

    /**
     * Register a POST route.
     */
    public function post(string $uri, string $controller, string $method): void
    {
        $this->addRoute('POST', $uri, $controller, $method);
    }

    /**
     * Register a PUT route.
     */
    public function put(string $uri, string $controller, string $method): void
    {
        $this->addRoute('PUT', $uri, $controller, $method);
    }

    /**
     * Register a PATCH route.
     */
    public function patch(string $uri, string $controller, string $method): void
    {
        $this->addRoute('PATCH', $uri, $controller, $method);
    }

    /**
     * Register a DELETE route.
     */
    public function delete(string $uri, string $controller, string $method): void
    {
        $this->addRoute('DELETE', $uri, $controller, $method);
    }

    /**
     * Store a route.
     */
    private function addRoute(
        string $requestMethod,
        string $uri,
        string $controller,
        string $method
    ): void {
        $this->routes[] = [
            'method' => strtoupper($requestMethod),
            'uri' => $this->normalize($uri),
            'controller' => $controller,
            'action' => $method,
        ];
    }

    /**
     * Return all registered routes.
     */
    public function routes(): array
    {
        return $this->routes;
    }

    /**
     * Dispatch the request.
     */
    public function dispatch(
        string $requestMethod,
        string $requestUri
    ): void {
        $requestMethod = strtoupper($requestMethod);
        $requestUri = $this->normalize($requestUri);

        foreach ($this->routes as $route) {

            if ($route['method'] !== $requestMethod) {
                continue;
            }

            $pattern = preg_replace(
                '/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/',
                '([^/]+)',
                $route['uri']
            );

            $pattern = '#^' . $pattern . '$#';

            if (!preg_match($pattern, $requestUri, $matches)) {
                continue;
            }

            array_shift($matches);

            $this->invoke(
                $route['controller'],
                $route['action'],
                $matches
            );

            return;
        }

        Response::notFound();
    }

    /**
     * Invoke a controller action.
     */
    private function invoke(
        string $controllerClass,
        string $method,
        array $parameters
    ): void {

        if (!class_exists($controllerClass)) {
            throw new RuntimeException(
                "Controller not found: {$controllerClass}"
            );
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            throw new RuntimeException(
                "Method {$method} not found in {$controllerClass}"
            );
        }

        $controller->{$method}(...$parameters);
    }

    /**
     * Normalize a URI.
     */
    private function normalize(string $uri): string
    {
        $path = parse_url($uri, PHP_URL_PATH);

        if (!is_string($path)) {
            return '/';
        }

        $path = '/' . trim($path, '/');

        return $path === '/' ? '/' : rtrim($path, '/');
    }
}
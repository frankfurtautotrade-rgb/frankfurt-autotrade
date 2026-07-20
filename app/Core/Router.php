<?php

namespace Core;

use Core\Response;

class Router
{
    /**
     * Registered routes
     *
     * @var array
     */
    private array $routes = [];

    /**
     * Register GET route
     */
    public function get(string $uri, string $controller, string $method): void
    {
        $this->addRoute('GET', $uri, $controller, $method);
    }

    /**
     * Register POST route
     */
    public function post(string $uri, string $controller, string $method): void
    {
        $this->addRoute('POST', $uri, $controller, $method);
    }

    /**
     * Store route
     */
    private function addRoute(
        string $requestMethod,
        string $uri,
        string $controller,
        string $method
    ): void {
        $this->routes[] = [
            'requestMethod' => strtoupper($requestMethod),
            'uri'           => $this->normalize($uri),
            'controller'    => $controller,
            'method'        => $method,
        ];
    }

    /**
     * Return all routes
     */
    public function routes(): array
    {
        return $this->routes;
    }

    /**
     * Dispatch current request
     */
    public function dispatch(string $requestMethod, string $requestUri): void
    {
        $requestMethod = strtoupper($requestMethod);
        $requestUri    = $this->normalize($requestUri);

        foreach ($this->routes as $route) {

            if ($route['requestMethod'] !== $requestMethod) {
                continue;
            }

            $pattern = preg_replace(
                '/\{[a-zA-Z_]+\}/',
                '([^/]+)',
                $route['uri']
            );

            $pattern = '#^' . $pattern . '$#';

            if (!preg_match($pattern, $requestUri, $matches)) {
                continue;
            }

            array_shift($matches);

            $controller = new $route['controller'];

            $method = $route['method'];

            if (!method_exists($controller, $method)) {
                throw new \Exception(
                    "Method {$method} not found in controller " . get_class($controller)
                );
            }

            $controller->$method(...$matches);

            return;
        }

        Response::notFound();
    }

    /**
     * Normalize URI
     */
    private function normalize(string $uri): string
    {
        $uri = parse_url($uri, PHP_URL_PATH);

        $uri = rtrim($uri, '/');

        return $uri === '' ? '/' : $uri;
    }
}
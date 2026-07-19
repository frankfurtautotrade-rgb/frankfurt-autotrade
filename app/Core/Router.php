<?php

namespace Core;

use Core\Response;

class Router
{
    private array $routes = [];

    public function get(string $uri, string $controller, string $method): void
    {
        $this->addRoute('GET', $uri, $controller, $method);
    }

    public function post(string $uri, string $controller, string $method): void
    {
        $this->addRoute('POST', $uri, $controller, $method);
    }

    private function addRoute(
        string $requestMethod,
        string $uri,
        string $controller,
        string $method
    ): void {
        $this->routes[] = [
            'requestMethod' => $requestMethod,
            'uri'           => $uri,
            'controller'    => $controller,
            'method'        => $method,
        ];
    }

    public function routes(): array
    {
        return $this->routes;
    }

public function dispatch(string $requestMethod, string $requestUri): void
{
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

            $controller->$method(...$matches);

            return;
    }

Response::notFound();

}

}
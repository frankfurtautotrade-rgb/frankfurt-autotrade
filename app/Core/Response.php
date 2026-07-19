<?php

namespace Core;

class Response
{
    public static function redirect(string $url): never
    {
        header("Location: {$url}");
        exit;
    }

    public static function notFound(): never
    {
        http_response_code(404);

        echo '<h1>404 - Page Not Found</h1>';

        exit;
    }

    public static function forbidden(): never
    {
        http_response_code(403);

        echo '<h1>403 - Forbidden</h1>';

        exit;
    }

    public static function json(array $data, int $status = 200): never
    {
        http_response_code($status);

        header('Content-Type: application/json');

        echo json_encode($data);

        exit;
    }
}
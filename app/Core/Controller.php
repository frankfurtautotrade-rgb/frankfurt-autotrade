<?php

namespace Core;

abstract class Controller
{
    /**
     * Render a view
     */
protected function view(string $view, array $data = []): void
{
    extract($data);

    $viewPath = __DIR__ . '/../Views/' . str_replace('.', '/', $view) . '.php';

    if (!file_exists($viewPath)) {
        throw new \RuntimeException("View not found: {$view}");
    }

    ob_start();

    require $viewPath;

    $content = ob_get_clean();

    require __DIR__ . '/../Views/layouts/admin.php';
}

}
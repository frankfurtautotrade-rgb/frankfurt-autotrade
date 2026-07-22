<?php

declare(strict_types=1);

namespace Core;

use RuntimeException;

abstract class Controller
{
    /**
     * Render a view inside a layout.
     *
     * @param string $view   View path (e.g. "admin/dashboard/index")
     * @param array  $data   Data passed to the view
     * @param string $layout Layout name (default: admin)
     */
    protected function view(
        string $view,
        array $data = [],
        string $layout = 'admin'
    ): void {
        extract($data, EXTR_SKIP);

        $viewPath = APP_PATH . '/Views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            throw new RuntimeException("View not found: {$viewPath}");
        }

        ob_start();

        require $viewPath;

        $content = ob_get_clean();

        $layoutPath = APP_PATH . '/Views/layouts/' . $layout . '.php';

        if (!file_exists($layoutPath)) {
            throw new RuntimeException("Layout not found: {$layoutPath}");
        }

        require $layoutPath;
    }
}
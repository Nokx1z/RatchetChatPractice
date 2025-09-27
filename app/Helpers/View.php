<?php
namespace App\Helpers;

class View
{
    public static function render(string $template, array $data = []): string
    {
        $viewBase = dirname(__DIR__) . '/views/';
        $layout = $viewBase . 'layouts/base.php';
        $file = $viewBase . $template . '.php';
        if (!file_exists($file)) {
            http_response_code(500);
            return 'View not found: ' . htmlspecialchars($template);
        }
        extract($data, EXTR_SKIP);
        ob_start();
        include $file;
        $content = ob_get_clean();

        if (file_exists($layout)) {
            ob_start();
            include $layout;
            return (string) ob_get_clean();
        }
        return $content;
    }
}

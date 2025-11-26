<?php
class BaseController {
    protected function render(string $viewPath, array $data = []) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        extract($data, EXTR_SKIP);
        $fullView = __DIR__ . '/../views/' . $viewPath . '.php';
        // capture view output
        ob_start();
        if (file_exists($fullView)) {
            require $fullView;
        } else {
            echo "View not found: " . htmlspecialchars($fullView);
        }
        $content = ob_get_clean();
        // include layout which will use $content
        $layout = __DIR__ . '/../views/layout.php';
        if (file_exists($layout)) require $layout;
        else echo $content;
    }
}

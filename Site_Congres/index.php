<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$controller = $_GET["c"] ?? "home";
$action = $_GET["a"] ?? "index";

$controllerName = ucfirst($controller) . "Controller";
$controllerFile = __DIR__ . "/app/controllers/" . $controllerName . ".php";

    // L'utilisateur est connecté

if (file_exists($controllerFile)) {
    require $controllerFile;
    $ctrl = new $controllerName();
    if (method_exists($ctrl, $action)) {
        $ctrl->$action();
    } else {
        echo "Action introuvable";
    }
} else {
    echo "Contrôleur introuvable";
}

?>
<?php
// Front controller
header("Content-Type: text/html; charset=UTF-8");
require_once __DIR__ . '/config/Database.php';

$controller = $_GET['c'] ?? 'home';
$action = $_GET['a'] ?? 'index';

$db = (new Database())->getConnection();

// map controller name to class/file
switch ($controller) {
    case 'auth':
        require_once __DIR__ . '/controllers/AuthController.php';
        $ctrl = new AuthController($db);
        if ($action === 'login') $ctrl->showLogin($_POST);
        elseif ($action === 'register') $ctrl->showRegister($_POST);
        elseif ($action === 'logout') { session_start(); session_unset(); session_destroy(); header('Location: index.php'); }
        else $ctrl->showLogin($_POST);
        break;
    case 'account':
        require_once __DIR__ . '/controllers/AccountController.php';
        $ctrl = new AccountController($db);
        if ($action === 'monespace') $ctrl->monEspace();
        else $ctrl->monEspace();
        break;
    case 'admin':
        require_once __DIR__ . '/controllers/AdminController.php';
        $ctrl = new AdminController($db);
        if ($action === 'create' && isset($_GET['id'])) {
            $ctrl->createFacture();
        } elseif ($action === 'delete' && isset($_GET['id'])) {
            $ctrl->deleteFacture();
        } else {
            $ctrl->index();
        }
        break;
    case 'facture':
        require_once __DIR__ . '/controllers/FactureController.php';
        $ctrl = new FactureController($db);
        if (($action ?? '') === 'pdf') $ctrl->pdf();
        break;
    default:
        if ($controller === 'home') {
            // only agents can view this
            if (session_status() === PHP_SESSION_NONE) session_start();
            if (empty($_SESSION['is_agent']) || !$_SESSION['is_agent']) {
                echo '<h1>Accès refusé</h1>';
                echo '<p>Vous devez être connecté en tant qu\'agent pour voir cette page. <a href="index.php?c=auth&a=login">Se connecter</a></p>';
            } else {
                require_once __DIR__ . '/repository/CongressisteRepository.php';
                $congz = new CongressisteRepository($db);
                $rows = $congz->findNonFactures();
                echo '<h1>Congressistes sans factures</h1>';
                echo '<pre>' . htmlspecialchars(print_r($rows, true)) . '</pre>';
            }
        } else {
            // unknown controller -> 404
            http_response_code(404);
            require_once __DIR__ . '/views/404.php';
        }
}


<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../repository/AuthRepository.php';
require_once __DIR__ . '/../repository/FactureRepository.php';

class AccountController extends BaseController {
    private $authRepo;
    private $factureRepo;
    public function __construct(PDO $db) {
        $this->authRepo = new AuthRepository($db);
        $this->factureRepo = new FactureRepository($db);
    }

    public function monEspace() {
        session_start();
        if (empty($_SESSION['user_id'])) { header('Location: login.php'); exit; }
        $user = $this->authRepo->findById((int)$_SESSION['user_id']);
        if (!$user) { echo 'Utilisateur introuvable'; exit; }
        $facturesIds = $this->factureRepo->findByCongressisteId($user->id_congressiste);
        $factures = [];
        foreach ($facturesIds as $fid) {
            $factures[] = $this->factureRepo->findById($fid);
        }
        $this->render('account/monespace', ['user' => $user, 'factures' => $factures]);
    }
}

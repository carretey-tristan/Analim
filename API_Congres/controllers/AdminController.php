<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../repository/CongressisteRepository.php';
require_once __DIR__ . '/../repository/FactureRepository.php';

class AdminController extends BaseController {
    private $congRepo;
    private $factRepo;
    public function __construct(PDO $db) {
        $this->congRepo = new CongressisteRepository($db);
        $this->factRepo = new FactureRepository($db);
    }

    public function index() {
        session_start();
        if (empty($_SESSION['user_id']) || $_SESSION['user_id'] != 25) { echo 'Accès admin réservé'; exit; }
    $users = $this->congRepo->findAll();
    $statut = isset($_GET['statut']) ? $_GET['statut'] : null; // 'reglee', 'non_reglee' or null
        $factures = $this->factRepo->findFiltered($statut);
        // normalize rows so view can rely on consistent keys (assoc array)
        $normalized = [];
        foreach ($factures as $row) {
            // support object or array
            if (is_object($row)) $row = get_object_vars($row);
            $normalized[] = [
                'id_facture' => $row['id_facture'] ?? ($row['ID_FACTURE'] ?? null),
                'date_facture' => $row['date_facture'] ?? ($row['DATE_FACTURE'] ?? null),
                'statut_reglement' => $row['statut_reglement'] ?? ($row['STATUT_REGLEMENT'] ?? 0),
                'id_congressiste' => $row['id_congressiste'] ?? null,
                'nom_congressiste' => $row['nom_congressiste'] ?? ($row['nom'] ?? ''),
                'prenom_congressiste' => $row['prenom_congressiste'] ?? ($row['prenom'] ?? ''),
                'cout_hotel' => isset($row['cout_hotel']) ? (float)$row['cout_hotel'] : 0.0,
                'total_sessions' => isset($row['total_sessions']) ? (float)$row['total_sessions'] : 0.0,
                'total_activites' => isset($row['total_activites']) ? (float)$row['total_activites'] : 0.0,
                'montant_acompte' => isset($row['montant_acompte']) ? (float)$row['montant_acompte'] : 0.0,
            ];
        }
        $factures = $normalized;
        $noInvoice = $this->congRepo->findNonFactures();
        $previews = [];
        foreach ($noInvoice as $c) {
            $preview = $this->factRepo->previewForCongressiste($c->id_congressiste);
            if ($preview) {
                $previews[] = $preview;
            }
        }
        $this->render('admin/index', ['users' => $users, 'factures' => $factures, 'noInvoicePreviews' => $previews]);
    }

    public function createFacture() {
        session_start();
        if (empty($_SESSION['user_id']) || $_SESSION['user_id'] != 25) { echo 'Accès admin réservé'; exit; }
    $id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
        if ($id <= 0) { header('Location: index.php?c=admin'); exit; }
        $res = $this->factRepo->create($id);
        // redirect back to admin page
        header('Location: index.php?c=admin'); exit;
    }

    public function deleteFacture() {
        session_start();
        if (empty($_SESSION['user_id']) || $_SESSION['user_id'] != 25) { echo 'Accès admin réservé'; exit; }
        $id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
        if ($id <= 0) { header('Location: index.php?c=admin'); exit; }

        $facture = $this->factRepo->findById($id);
        if (!$facture) {
            $_SESSION['admin_error'] = "Facture introuvable.";
            header('Location: index.php?c=admin'); exit;
        }

        $hasPayments = false;
        if (!empty($facture->statut_reglement)) $hasPayments = true;
        if (isset($facture->total_regle) && (float)$facture->total_regle > 0.0) $hasPayments = true;

        if ($hasPayments) {
            $_SESSION['admin_error'] = "Suppression refusée : la facture a un règlement enregistré.";
            header('Location: index.php?c=admin'); exit;
        }

        $ok = $this->factRepo->delete($id);
        if ($ok) $_SESSION['admin_success'] = "Facture #{$id} supprimée.";
        else $_SESSION['admin_error'] = "Erreur lors de la suppression.";

        header('Location: index.php?c=admin'); exit;
    }
}

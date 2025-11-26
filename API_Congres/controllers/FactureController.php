<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../repository/FactureRepository.php';
require_once __DIR__ . '/../vendor/autoload.php'; // <-- composer autoload

use Dompdf\Dompdf;

class FactureController extends BaseController {
    private $db;
    public function __construct(PDO $db) { $this->db = $db; }

    public function pdf() {
        $id = (int)($_GET['id'] ?? 0);
        $repo = new FactureRepository($this->db);
        $facture = $repo->findById($id);
        if (!$facture) { http_response_code(404); echo 'Facture introuvable'; return; }

        // Authorization: only the facture owner or an agent may view the PDF
        if (session_status() === PHP_SESSION_NONE) session_start();
        $currentUserId = $_SESSION['user_id'] ?? null;
        $isAgent = $_SESSION['is_agent'] ?? false;

        // facture->congressiste may be object or array; normalize id
        $ownerId = null;
        if (isset($facture->congressiste)) {
            $c = $facture->congressiste;
            if (is_object($c) && isset($c->id_congressiste)) {
                $ownerId = (int)$c->id_congressiste;
            } elseif (is_array($c) && array_key_exists('id_congressiste', $c)) {
                $ownerId = (int)$c['id_congressiste'];
            }
        } elseif (isset($facture->id_congressiste)) {
            // repository might expose direct field
            $ownerId = (int)$facture->id_congressiste;
        }

        if (!$isAgent && ($currentUserId === null || (int)$currentUserId !== $ownerId)) {
            http_response_code(403);
            echo 'Accès refusé : vous ne pouvez voir que vos propres factures.';
            return;
        }

        ob_start();
        $factureLocal = $facture;
        include __DIR__ . '/../views/facture/pdf.php'; // ta vue HTML de facture
        $html = ob_get_clean();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4','portrait');
        $dompdf->render();
        $dompdf->stream("facture_{$facture->id_facture}.pdf", ['Attachment' => 0]); // 0 = affichage inline
    }
}
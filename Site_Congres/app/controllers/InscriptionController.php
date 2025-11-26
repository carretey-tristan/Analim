<?php
require_once __DIR__ . '/../ApiClient.php';

class InscriptionController {
    public function list() {
        $api = new ApiClient();
        $conferences = [];
        $seminaires = $api->getSeminaires();
        if (isset($_SESSION["participant_id"])) {
            $ParticipantId = intval($_SESSION["participant_id"]);
            $conferences = $api->getMesInscriptions($ParticipantId);
        }
        include __DIR__ . '/../views/layout.php';

    }
    public function inscrire() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['participant_id']) && isset($_POST['conference_id'])) {
            $api = new ApiClient();
            $participant_id = $_SESSION['participant_id'];
            $conference_id = $_POST['conference_id'];
            $result = $api->inscrire($participant_id, $conference_id);
            echo $_POST['redirect'];
            header('Location: ' . $_POST['redirect']);
            exit();
        } else {
            // Redirige ou affiche une erreur si non connecté ou conference_id manquant
            header('Location: index.php?c=auth&a=login');
            exit();
        }
    }
    public function desinscrire() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['participant_id']) && isset($_POST['conference_id'])) {
            $api = new ApiClient();
            $participant_id = $_SESSION['participant_id'];
            $conference_id = $_POST['conference_id'];
            $result = $api->desinscrire($participant_id, $conference_id);

            // Redirection vers la page d'origine
            echo $_POST['redirect'];
            header('Location: ' . $_POST['redirect']);
            exit();
        } else {
            header('Location: index.php?c=auth&a=login');
            exit();
        }
    }
}
?>
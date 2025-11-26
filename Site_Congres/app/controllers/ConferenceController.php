<?php

require_once __DIR__."/../ApiClient.php";

class ConferenceController {
    public function list() {
        $api = new ApiClient();
        if (isset($_GET["seminaire_id"]) && $_GET["seminaire_id"] !== "None") {
            $seminaireId = intval($_GET["seminaire_id"]);
            $conferences = $api->getConferences($seminaireId);
        } else {
            $conferences = $api->getConferences();
        }
        $seminaires = $api->getSeminaires();
        if (isset($_SESSION["participant_id"])) {
            $ParticipantId = intval($_SESSION["participant_id"]);
            $participing = $api->participing($ParticipantId);
        } else {
            $participing = [];
        }
        include __DIR__ . '/../views/layout.php';
    }
        
}
?>
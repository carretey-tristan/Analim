<?php
header("Content-Type: application/json; charset=UTF-8");

require_once "config/database.php";
require_once "repository/conferenceRepository.php";
require_once "repository/participantRepository.php";

$database = new Database();
$db = $database->getConnection();

$endpoint = $_GET["endpoint"] ?? "";
$method = $_SERVER["REQUEST_METHOD"];

// Fonction utilitaire pour récupérer les données POST ou JSON
function getRequestData() {
    $contentType = $_SERVER["CONTENT_TYPE"] ?? '';
    if (stripos($contentType, 'application/json') !== false) {
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);
        if (is_array($data)) return $data;
    }
    return $_POST;
}

switch ($endpoint) {
    case "conferences":
        $repo = new ConferenceRepository($db);
        $data = getRequestData();
        if(isset($data['seminaire_id'])) {
            $confs = $repo->findBySeminaire($data['seminaire_id']);
        } else{
            $confs = $repo->findAll();
        }
        echo json_encode($confs, JSON_PRETTY_PRINT);
        break;
    case "seminaires":
        $repo = new ConferenceRepository($db);
        $seminaires = $repo->getSeminaires();
        echo json_encode($seminaires, JSON_PRETTY_PRINT);
        break;
    case "inscriptions":
        $repo = new ParticipantRepository($db);
        $data = getRequestData();
        $success = $repo->inscrire($data['participant_id'], $data['conference_id']);
        echo json_encode(['success' => $success], JSON_PRETTY_PRINT);
        break;
    case "login":
        $repo = new ParticipantRepository($db);
        $data = getRequestData();
        $participant = $repo->login($data);
        if ($participant) {
            echo json_encode($participant, JSON_PRETTY_PRINT);
        }
        break;
    case "register":
        $repo = new ParticipantRepository($db);
        $data = getRequestData();
        $success = $repo->register($data);
        echo json_encode(['success' => $success], JSON_PRETTY_PRINT);
        break;
    case "mesinscriptions":
        $repo = new ParticipantRepository($db);
        $data = getRequestData();
        $inscriptions = $repo->getInscriptions($data);
        echo json_encode($inscriptions, JSON_PRETTY_PRINT);
        break;
    case "desinscrire":
        $repo = new ParticipantRepository($db);
        $data = getRequestData();
        $success = $repo->desinscrire($data);
        echo json_encode(['success' => $success], JSON_PRETTY_PRINT);
        break;
    case "participing":
        $repo = new ParticipantRepository($db);
        $data = getRequestData();
        if (isset($data['participant_id'])) {
            $Participating = $repo->participing($data);
            echo json_encode($Participating, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(['error' => 'participant_id required'], JSON_PRETTY_PRINT);
        }
        break;
}
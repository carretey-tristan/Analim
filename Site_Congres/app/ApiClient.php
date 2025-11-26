<?php
// Classe à compléter pour communiquer avec l’API REST
class ApiClient {
    private $baseUrl = "http://localhost/api/api.php?endpoint=";

    // Méthode générique pour envoyer une requête à l’API
    private function request($endpoint, $method = "GET", $data = null) {
        $url = $this->baseUrl . $endpoint;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = [];
        if ($method === "POST" || $method === "PUT" || $method === "DELETE") {
            $headers[] = "Content-Type: application/json";
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            if ($data) {
                $jsonData = json_encode($data);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
            }
        }

        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

    // Authentification
    public function login($mail, $password) {
        $data = [
            'email' => $mail,
            'password' => $password
        ];
        return $this->request("login", "POST", $data);
    }

    // Récupérer les conférences (optionnellement par séminaire)
    public function getConferences($seminaire_id = '') {
        if ($seminaire_id) {
            return $this->request("conferences", "POST", ['seminaire_id' => $seminaire_id]);
        } else {
            return $this->request("conferences", "GET");
        }
    }

    // Inscription à une conférence
    public function inscrire($participant_id, $conference_id) {
        $data = [
            'participant_id' => $participant_id,
            'conference_id' => $conference_id
        ];
        return $this->request("inscriptions", "POST", $data);
    }

    // Récupérer les inscriptions d’un participant
    public function getMesInscriptions($participant_id) {
        $data = [
            'participant_id' => $participant_id,
        ];
        return $this->request("mesinscriptions", "POST", $data);
    }

    // Enregistrement d’un participant
    public function register($email, $password, $nom, $prenom, $profession, $ville) {
        $data = [
            'email' => $email,
            'password' => $password,
            'nom' => $nom,
            'prenom' => $prenom,
            'profession' => $profession,
            'ville' => $ville
        ];
        return $this->request("register", "POST", $data);
    }

    // Récupérer les séminaires
    public function getSeminaires() {
        return $this->request("seminaires", "GET");
    }

    public function desinscrire($participant_id, $conference_id) {
        $data = [
            'participant_id' => $participant_id,
            'conference_id' => $conference_id
        ];
        return $this->request("desinscrire", "POST", $data);
    }

    public function participing($participant_id) {
        $data = [
            'participant_id' => $participant_id
        ];
        return $this->request("participing", "POST", $data);
    }
}
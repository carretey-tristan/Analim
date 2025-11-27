<?php
require_once __DIR__ . '/../classe/conference.php';
require_once __DIR__ . '/../classe/intervenant.php';
require_once __DIR__ . '/../classe/seminaire.php';

class ParticipantRepository
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Inscription à une conférence

    public function inscrire($participant_id, $conference_id)
    {
        $sql = 'INSERT INTO participer (id, participant_id) VALUES ( :conference_id, :participant_id)';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':participant_id', $participant_id);
        $stmt->bindParam(':conference_id', $conference_id);
        if($stmt->execute()) {
            $message = [
                'message' => "Inscription réussie",
            ];
            return $message;
        } else {
            $message = [
                'message' => "Inscription échouée",
            ];
            return $message;
        }
    }

    // Authentification

    public function login($data)
    {
        $email = $data['email'];
        $pass = $data['password'];
        $sql = "SELECT * FROM participant WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        if ($result && password_verify($pass, $result->password)) { 
            $message = [
                'success' => True,
                'id' => $result->id,
            ];
            return $message;
        } else {
            $message = [
                'success' => False,
            ];
            return $message;
        }
    }

    // Inscription d'un nouveau participant

    public function register($data)
    {
        $pass = $data['password'];
        $hash = password_hash($pass, PASSWORD_BCRYPT);
        $sql = 'INSERT INTO participant (email, password, nom, prenom, profession, ville) VALUES (:email, :password, :nom, :prenom, :profession, :ville)';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hash);
        $stmt->bindParam(':nom', $data['nom']);
        $stmt->bindParam(':prenom', $data['prenom']);
        $stmt->bindParam(':profession', $data['profession']);
        $stmt->bindParam(':ville', $data['ville']);
        if ($stmt->execute()) {
            $message = [
                'success' => True,
                
            ];
            return $message;
        } else {
            $message = [
                'success' => False,
      
            ];
            return $message;
        }
    }

    // Récupérer les inscriptions d’un participant

    public function getInscriptions($data)
    {
        $sql = "SELECT 
                    conference.id AS conference_id,
                    conference.description,
                    conference.salle,
                    conference.nbplaces,
                    creneau.heure,
                    intervenant.id AS intervenant_id,
                    intervenant.nom AS intervenant_nom,
                    intervenant.prenom AS intervenant_prenom,
                    seminaire.id AS seminaire_id,
                    seminaire.intitule AS seminaire_intitule
                FROM conference 
                JOIN intervenant ON conference.intervenant_id = intervenant.id
                JOIN creneau ON conference.creneau_id = creneau.id
                JOIN seminaire ON creneau.seminaire_id = seminaire.id   
                JOIN participer ON conference.id = participer.id
                WHERE participant_id = :participant_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':participant_id', $data['participant_id']);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function desinscrire($data)
    {
        $sql = "DELETE FROM participer WHERE participant_id = :participant_id AND id = :conference_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':participant_id', $data['participant_id']);
        $stmt->bindParam(':conference_id', $data['conference_id']);
        return $stmt->execute();
    }
    public function participing($data)
    {
        $sql = "SELECT * FROM participer WHERE participant_id = :participant_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':participant_id', $data['participant_id']);
        $stmt->execute();
        $ids = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        $result = [];
        foreach ($ids as $id) {
            $result[] = ['id' => $id];
        }
        return $result;
    }
}
?>
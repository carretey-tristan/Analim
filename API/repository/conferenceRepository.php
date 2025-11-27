<?php
require_once __DIR__ . '/../classe/conference.php';
require_once __DIR__ . '/../classe/intervenant.php';
require_once __DIR__ . '/../classe/seminaire.php';

class ConferenceRepository {
    private $conn;

    public function __construct( $db ) {
        $this->conn = $db;
    }

    public function findAll() {
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
                JOIN seminaire ON creneau.seminaire_id = seminaire.id";
        $stmt = $this->conn->prepare( $sql );
        $stmt->execute();
        $rows = $stmt->fetchAll( PDO::FETCH_OBJ );
        $conferences = [];

        foreach ( $rows as $row ) {
            $intervenant = new Intervenant( $row->intervenant_id, $row->intervenant_nom, $row->intervenant_prenom );
            $seminaire = new Seminaire( $row->seminaire_id, $row->seminaire_intitule );
            $conf = new Conference(
                $row->conference_id,
                $row->description,
                $row->salle,
                $row->nbplaces,
                $intervenant,
                $row->heure,
                $seminaire
            );
            $conferences[] = $conf;
        }
        return $conferences;
    }

    // Récupérer la liste des séminaires

    public function getSeminaires() {

        $sql = "SELECT 
                    seminaire.id AS seminaire_id,
                    seminaire.intitule AS seminaire_intitule
                FROM seminaire";
        $stmt = $this->conn->prepare( $sql );
        $stmt->execute();
        $rows = $stmt->fetchAll( PDO::FETCH_OBJ );
        $seminaires = [];

        foreach ( $rows as $row ) {
            $seminaire = new Seminaire( $row->seminaire_id, $row->seminaire_intitule );
            $seminaires[] = $seminaire;
        }
        return $seminaires;
    }

    // Récupérer les conférences d’un séminaire donné

    public function findBySeminaire( $seminaire_id ) {
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
                WHERE seminaire.id = :seminaire_id";
        $stmt = $this->conn->prepare( $sql );
        $stmt->bindParam( ':seminaire_id', $seminaire_id );
        $stmt->execute();
        $rows = $stmt->fetchAll( PDO::FETCH_OBJ );
        $conferences = [];

        foreach ( $rows as $row ) {
            $intervenant = new Intervenant( $row->intervenant_id, $row->intervenant_nom, $row->intervenant_prenom );
            $seminaire = new Seminaire( $row->seminaire_id, $row->seminaire_intitule );
            $conf = new Conference(
                $row->conference_id,
                $row->description,
                $row->salle,
                $row->nbplaces,
                $intervenant,
                $row->heure,
                $seminaire
            );
            $conferences[] = $conf;
        }
        return $conferences;
    }

}
?>
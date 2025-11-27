<?php
require_once __DIR__ . '/../classe/facture.php';
require_once __DIR__ . '/../classe/Congressiste.php';
require_once __DIR__ . '/../classe/OrganismePayeur.php';

class FactureRepository {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }


    public function findFiltered($statut = null) {
                $SQL = "SELECT 
                                        f.id_facture, f.date_facture, f.statut_reglement,
                                        c.id_congressiste,
                                        c.nom AS nom_congressiste, c.prenom AS prenom_congressiste,
                                        IFNULL(op.nom_organisme, 'Congressiste') AS payeur,
                                        h.prix AS cout_hotel,
                                        IFNULL((SELECT SUM(s.prix_session) FROM PARTICIPATION_SESSION ps JOIN SESSION s ON ps.id_session = s.id_session WHERE ps.id_congressiste = c.id_congressiste),0) AS total_sessions,
                                        IFNULL((SELECT SUM(a.prix_activite) FROM PARTICIPATION_ACTIVITE pa JOIN ACTIVITE a ON pa.id_activite = a.id_activite WHERE pa.id_congressiste = c.id_congressiste),0) AS total_activites,
                                        (CASE WHEN c.acompte THEN 100.00 ELSE 0.00 END) AS montant_acompte
                                    FROM FACTURE f
                                    JOIN CONGRESSISTE c ON f.id_congressiste = c.id_congressiste
                                    LEFT JOIN ORGANISME_PAYEUR op ON f.id_organisme = op.id_organisme
                                    LEFT JOIN HOTEL h ON c.id_hotel = h.id_hotel";

        if ($statut === 'reglee') {
            $SQL .= " WHERE f.statut_reglement = 1"; // TRUE
        } elseif ($statut === 'non_reglee') {
            $SQL .= " WHERE f.statut_reglement = 0"; // FALSE
        }
        
        $stmt = $this->db->prepare($SQL);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByCongressisteId(int $id): array {
        $sql = "SELECT id_facture FROM FACTURE WHERE id_congressiste = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }


    public function findById($id_facture) {
        $SQL = "
            SELECT
                f.id_facture, f.date_facture, f.statut_reglement,
                c.id_congressiste, c.nom, c.prenom, c.adresse, c.email, c.acompte,
                op.id_organisme, op.nom_organisme, op.adresse_organisme,
                h.prix AS cout_hotel,
                
                (SELECT SUM(s.prix_session) FROM PARTICIPATION_SESSION ps
                 JOIN SESSION s ON ps.id_session = s.id_session
                 WHERE ps.id_congressiste = f.id_congressiste) AS total_sessions,
                 
                (SELECT SUM(a.prix_activite) FROM PARTICIPATION_ACTIVITE pa
                 JOIN ACTIVITE a ON pa.id_activite = a.id_activite
                 WHERE pa.id_congressiste = f.id_congressiste) AS total_activites
                 

            FROM FACTURE f
            JOIN CONGRESSISTE c ON f.id_congressiste = c.id_congressiste
            LEFT JOIN ORGANISME_PAYEUR op ON f.id_organisme = op.id_organisme
            LEFT JOIN HOTEL h ON c.id_hotel = h.id_hotel
            WHERE f.id_facture = :id_facture
        ";

        $stmt = $this->db->prepare($SQL);
        $stmt->bindParam(':id_facture', $id_facture);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) return null;

        $facture = new Facture();
        $facture->id_facture = (int)$data['id_facture'];
        $facture->date_facture = $data['date_facture'];
        $facture->statut_reglement = (bool)$data['statut_reglement'];
        
        $facture->congressiste = new Congressiste();
        $facture->congressiste->id_congressiste = (int)$data['id_congressiste'];
        $facture->congressiste->nom = $data['nom'];
        $facture->congressiste->prenom = $data['prenom'];
        $facture->congressiste->acompte = (bool)$data['acompte'];
        $facture->congressiste->adresse = $data['adresse'];
        $facture->congressiste->email = $data['email'];
        
        if ($data['id_organisme']) {
            $facture->organisme_payeur = new OrganismePayeur();
            $facture->organisme_payeur->id_organisme = (int)$data['id_organisme'];
            $facture->organisme_payeur->nom_organisme = $data['nom_organisme'];
            $facture->organisme_payeur->adresse_organisme = $data['adresse_organisme'];
        }

        $facture->cout_hotel = (float)($data['cout_hotel'] ?? 0.0);
        $facture->total_sessions = (float)($data['total_sessions'] ?? 0.0);
        $facture->total_activites = (float)($data['total_activites'] ?? 0.0);
    // montant_acompte should be 100.0 when congressiste has paid an acompte flag (true)
    $facture->montant_acompte = !empty($data['acompte']) ? 100.00 : 0.0;
        
   
        $facture->total_regle = 0.0; 


        $facture->calculerTotaux();
        return $facture;
    }


    public function create($id_congressiste) {
        // ... (Vérification si facture existe déjà)
        $checkQuery = "SELECT id_facture FROM FACTURE WHERE id_congressiste = :id";
        $checkStmt = $this->db->prepare($checkQuery);
        $checkStmt->execute([':id' => $id_congressiste]);
        if ($checkStmt->fetch()) {
            http_response_code(409); // 409 Conflict
            return ['error' => 'Ce congressiste a déjà une facture.'];
        }

        // ... (Récupération de l'organisme)
        $orgQuery = "SELECT id_organisme FROM CONGRESSISTE WHERE id_congressiste = :id";
        $orgStmt = $this->db->prepare($orgQuery);
        $orgStmt->execute([':id' => $id_congressiste]);
        $id_organisme = $orgStmt->fetchColumn();

        // ... (Insertion)
        $insertQuery = "INSERT INTO FACTURE (date_facture, statut_reglement, id_organisme, id_congressiste)
                        VALUES (CURDATE(), FALSE, :id_organisme, :id_congressiste)";
        
        $stmt = $this->db->prepare($insertQuery);
        $success = $stmt->execute([
            ':id_organisme' => $id_organisme,
            ':id_congressiste' => $id_congressiste
        ]);

        if ($success) {
            $newId = $this->db->lastInsertId();
            return $this->findById($newId); // Retourne la nouvelle facture
        } else {
            http_response_code(500);
            return ['error' => 'Erreur lors de la création de la facture.'];
        }
    }

    /**
     * Retourne un objet Facture pré-rempli (estimation) pour afficher un résumé
     * sans créer la facture en base.
     */
    public function previewForCongressiste(int $id_congressiste) {
        $SQL = "
            SELECT
                c.id_congressiste, c.nom, c.prenom, c.acompte,
                h.prix AS cout_hotel,
                (SELECT IFNULL(SUM(s.prix_session),0) FROM PARTICIPATION_SESSION ps JOIN SESSION s ON ps.id_session = s.id_session WHERE ps.id_congressiste = c.id_congressiste) AS total_sessions,
                (SELECT IFNULL(SUM(a.prix_activite),0) FROM PARTICIPATION_ACTIVITE pa JOIN ACTIVITE a ON pa.id_activite = a.id_activite WHERE pa.id_congressiste = c.id_congressiste) AS total_activites
            FROM CONGRESSISTE c
            LEFT JOIN HOTEL h ON c.id_hotel = h.id_hotel
            WHERE c.id_congressiste = :id
        ";

        $stmt = $this->db->prepare($SQL);
        $stmt->execute([':id' => $id_congressiste]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) return null;

        $facture = new Facture();
        $facture->congressiste = new Congressiste();
        $facture->congressiste->id_congressiste = (int)$data['id_congressiste'];
        $facture->congressiste->nom = $data['nom'];
        $facture->congressiste->prenom = $data['prenom'];
        $facture->congressiste->acompte = !empty($data['acompte']);

        $facture->cout_hotel = (float)($data['cout_hotel'] ?? 0.0);
        $facture->total_sessions = (float)($data['total_sessions'] ?? 0.0);
        $facture->total_activites = (float)($data['total_activites'] ?? 0.0);
    $facture->montant_acompte = $facture->congressiste->acompte ? 100.0 : 0.0;
        $facture->total_regle = 0.0;
        $facture->calculerTotaux();

        return $facture;
    }

    /**
     * Supprime une facture par son id
     * @return bool true si suppression effectuée
     */
    public function delete(int $id_facture): bool {
        $sql = "DELETE FROM FACTURE WHERE id_facture = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id_facture]);
    }

    /**
     * Indique si une facture a déjà un règlement (statut_reglement = 1)
     */
    public function hasReglement(int $id_facture): bool {
        $sql = "SELECT statut_reglement FROM FACTURE WHERE id_facture = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_facture]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return false;
        return !empty($row['statut_reglement']);
    }
}
?>
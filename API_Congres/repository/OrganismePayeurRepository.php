<?php
require_once __DIR__ . '/../classe/OrganismePayeur.php';

class OrganismePayeurRepository {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function findOrganismeByIdCongressiste(int $id): ?OrganismePayeur {
        $SQL = "SELECT * FROM ORGANISME_PAYEUR
                WHERE (SELECT id_organisme FROM CONGRESSISTE WHERE id_congressiste = :id) = id_organisme";
        $stmt = $this->db->prepare($SQL);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        $org = new OrganismePayeur();
        $org->id_organisme = isset($row['id_organisme']) ? (int)$row['id_organisme'] : 0;
        $org->nom_organisme = $row['nom_organisme'] ?? '';
        $org->adresse_organisme = $row['adresse_organisme'] ?? '';
        $org->email_organisme = $row['email_organisme'] ?? '';
        $org->type = $row['type'] ?? '';

        return $org;
    }
}
?>
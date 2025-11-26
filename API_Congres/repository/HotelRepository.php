<?php
require_once __DIR__ . '/../classe/Hotel.php';

class HotelRepository {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }
    
    public function findHotelsByIdCongressiste(int $id): ?Hotel {
        $SQL = "SELECT * FROM HOTEL WHERE (Select id_hotel FROM CONGRESSISTE WHERE id_congressiste = :id) = id_hotel";
        $stmt = $this->db->prepare($SQL);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        $hotel = new Hotel();
        $hotel->id_hotel = isset($row['id_hotel']) ? (int)$row['id_hotel'] : 0;
        $hotel->nom_hotel = $row['nom_hotel'] ?? '';
        $hotel->adresse_hotel = $row['adresse_hotel'] ?? '';
        $hotel->prix_supplement_petit_dejeuner = isset($row['prix_supplement_petit_dejeuner']) ? (float)$row['prix_supplement_petit_dejeuner'] : 0.0;
        $hotel->prix = isset($row['prix']) ? (float)$row['prix'] : 0.0;
        $hotel->etoile = isset($row['etoile']) ? (float)$row['etoile'] : 0.0;
        $hotel->chambre_disponible = isset($row['chambre_disponible']) ? (float)$row['chambre_disponible'] : 0.0;

        return $hotel;
    }
}
?>
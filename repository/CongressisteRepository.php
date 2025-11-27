<?php
require_once __DIR__ . '/../classe/Congressiste.php';
require_once __DIR__ . '/HotelRepository.php'; 
require_once __DIR__ . '/OrganismePayeurRepository.php'; 
class CongressisteRepository {
    private $db;
    private $hotelRepo;
    private $organismeRepo;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->hotelRepo = new HotelRepository($db);
        $this->organismeRepo = new OrganismePayeurRepository($db);
    }

    public function findAll() {
        $query = "SELECT * FROM CONGRESSISTE";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $out = [];
        foreach ($rows as $r) {
            $c = new Congressiste();
            $c->id_congressiste = (int)($r['id_congressiste'] ?? 0);
            $c->nom = $r['nom'] ?? '';
            $c->prenom = $r['prenom'] ?? '';
            $c->adresse = $r['adresse'] ?? '';
            $c->email = $r['email'] ?? '';
            $c->acompte = !empty($r['acompte']);
            $c->supplement_petit_dejeuner = !empty($r['supplement_petit_dejeuner']);
            $c->nb_etoile_souhaite = isset($r['nb_etoile_souhaite']) ? (int)$r['nb_etoile_souhaite'] : 0;

            // récupérer relations via repo (retour attendu : objet ou null)
            $hotel = $this->hotelRepo->findHotelsByIdCongressiste($c->id_congressiste);
            $c->setHotel($hotel);
            $organisme = $this->organismeRepo->findOrganismeByIdCongressiste($c->id_congressiste);
            $c->setOrganisme_payeur($organisme);

            $out[] = $c;
        }
        return $out;
    }
    public function findById(int $id): ?Congressiste {
        $query = "SELECT * FROM CONGRESSISTE WHERE id_congressiste = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        $c = new Congressiste();
        $c->id_congressiste = (int)($row['id_congressiste'] ?? 0);
        $c->nom = $row['nom'] ?? '';
        $c->prenom = $row['prenom'] ?? '';
        $c->adresse = $row['adresse'] ?? '';
        $c->email = $row['email'] ?? '';
        $c->acompte = !empty($row['acompte']);
        $c->supplement_petit_dejeuner = !empty($row['supplement_petit_dejeuner']);
        $c->nb_etoile_souhaite = isset($row['nb_etoile_souhaite']) ? (int)$row['nb_etoile_souhaite'] : 0;

        // relations via repos (retour attendu : objet ou null)
        $hotel = $this->hotelRepo->findHotelsByIdCongressiste($c->id_congressiste);
        $c->setHotel($hotel);
        $organisme = $this->organismeRepo->findOrganismeByIdCongressiste($c->id_congressiste);
        $c->setOrganisme_payeur($organisme);

        return $c;
    }

    public function findNonFactures() {
        $query = "SELECT c.* FROM CONGRESSISTE c
                  LEFT JOIN FACTURE f ON c.id_congressiste = f.id_congressiste
                  WHERE f.id_facture IS NULL
                  ORDER BY c.nom, c.prenom";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$rows) return [];

        $out = [];
        foreach ($rows as $r) {
            $c = new Congressiste();
            $c->id_congressiste = (int)($r['id_congressiste'] ?? 0);
            $c->nom = $r['nom'] ?? '';
            $c->prenom = $r['prenom'] ?? '';
            $c->adresse = $r['adresse'] ?? '';
            $c->email = $r['email'] ?? '';
            $c->acompte = !empty($r['acompte']);
            $c->supplement_petit_dejeuner = !empty($r['supplement_petit_dejeuner']);
            $c->nb_etoile_souhaite = isset($r['nb_etoile_souhaite']) ? (int)$r['nb_etoile_souhaite'] : 0;

            $hotel = $this->hotelRepo->findHotelsByIdCongressiste($c->id_congressiste);
            $c->setHotel($hotel);
            $organisme = $this->organismeRepo->findOrganismeByIdCongressiste($c->id_congressiste);
            $c->setOrganisme_payeur($organisme);

            $out[] = $c;
        }
        return $out;
    }
}
?>
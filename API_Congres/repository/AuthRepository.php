<?php
require_once __DIR__ . '/../classe/Congressiste.php';

class AuthRepository {
    private $db;
    public function __construct(PDO $db) { $this->db = $db; }

    public function findByEmail(string $email): ?Congressiste {
        $sql = "SELECT * FROM CONGRESSISTE WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;
        $c = new Congressiste();
        $c->id_congressiste = (int)($row['id_congressiste'] ?? 0);
        $c->nom = $row['nom'] ?? '';
        $c->prenom = $row['prenom'] ?? '';
        $c->adresse = $row['adresse'] ?? '';
        $c->email = $row['email'] ?? '';
    $c->password = $row['password'] ?? null;
        $c->acompte = !empty($row['acompte']);
        $c->supplement_petit_dejeuner = !empty($row['supplement_petit_dejeuner']);
        $c->nb_etoile_souhaite = isset($row['nb_etoile_souhaite']) ? (int)$row['nb_etoile_souhaite'] : 0;
        return $c;
    }

    public function findById(int $id): ?Congressiste {
        $sql = "SELECT * FROM CONGRESSISTE WHERE id_congressiste = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;
        $c = new Congressiste();
        $c->id_congressiste = (int)($row['id_congressiste'] ?? 0);
        $c->nom = $row['nom'] ?? '';
        $c->prenom = $row['prenom'] ?? '';
        $c->adresse = $row['adresse'] ?? '';
        $c->email = $row['email'] ?? '';
    $c->password = $row['password'] ?? null;
        $c->acompte = !empty($row['acompte']);
        $c->supplement_petit_dejeuner = !empty($row['supplement_petit_dejeuner']);
        $c->nb_etoile_souhaite = isset($row['nb_etoile_souhaite']) ? (int)$row['nb_etoile_souhaite'] : 0;
        return $c;
    }

    public function create(array $data): array {
        $sql = "INSERT INTO CONGRESSISTE (nom, prenom, adresse, email, password, acompte, supplement_petit_dejeuner, nb_etoile_souhaite) VALUES (:nom, :prenom, :adresse, :email, :password, :acompte, :supplement, :nb_etoile)";
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':adresse' => $data['adresse'],
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':acompte' => $data['acompte'] ?? 0,
            ':supplement' => $data['supplement'] ?? 0,
            ':nb_etoile' => $data['nb_etoile'] ?? 0,
        ]);
        if (!$success) return ['error' => 'Erreur création'];
        return ['id' => (int)$this->db->lastInsertId()];
    }
}

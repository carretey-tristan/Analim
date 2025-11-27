<?php
class Congressiste {
    public int $id_congressiste;
    public String $nom;
    public String $prenom;
    public String $adresse;
    public String $email;
    // stocke le hash du mot de passe (doit exister en base : password VARCHAR(255))
    // la colonne s'appelle `password` dans la base
    public ?string $password = null;
    public bool $acompte;
    public bool $supplement_petit_dejeuner;
    public int $nb_etoile_souhaite;
    public ?OrganismePayeur $organisme_payeur;
    public ?Hotel $hotel;
    public function __construct() {}

    /**
     * Set the value of hotel
     *
     * @return  self
     */ 
    public function setHotel($hotel)
    {
        $this->hotel = $hotel;

        return $this;
    }

    /**
     * Set the value of organisme_payeur
     *
     * @return  self
     */ 
    public function setOrganisme_payeur($organisme_payeur)
    {
        $this->organisme_payeur = $organisme_payeur;

        return $this;
    }
    }
?>
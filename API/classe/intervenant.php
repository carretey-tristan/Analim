<?php
class Intervenant {
    public $id;
    public $nom;
    public $prenom;

    public function __construct($id, $nom, $prenom) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
    }
}
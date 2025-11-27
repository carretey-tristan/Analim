<?php
class Conference {
    public $id;
    public $description;
    public $salle;
    public $nbplaces;
    public $intervenant;  // objet Intervenant
    public $horaire;      // créneau
    public $seminaire;    // objet Seminaire
 
    public function __construct($id, $description, $salle, $nbplaces, $intervenant, $horaire, $seminaire) {
        $this->id = $id;
        $this->description = $description;
        $this->salle = $salle;
        $this->nbplaces = $nbplaces;
        $this->intervenant = $intervenant;
        $this->horaire = $horaire;
        $this->seminaire = $seminaire;
    }
}
?>
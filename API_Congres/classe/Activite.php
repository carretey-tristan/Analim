<?php

use phpDocumentor\Reflection\Types\String_;
class Activite {
    public int $id_activite;
    public String $nom_activite;
    public String $description;
    public float $prix_activite;
    public String $date_activite;

    public function __construct() {}
}
?>
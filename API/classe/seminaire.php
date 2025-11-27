<?php
class Seminaire {
    public $id;
    public $intitule;

    public function __construct($id, $intitule) {
        $this->id = $id;
        $this->intitule = $intitule;
    }
}
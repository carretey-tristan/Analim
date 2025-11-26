<?php

class Facture {
    public $id_facture;
    public $date_facture;
    public $statut_reglement;

    public $congressiste;       
    public $organisme_payeur;


    public $cout_hotel = 0.0;
    public $total_sessions = 0.0;
    public $total_activites = 0.0;
    public $montant_acompte = 0.0;
    public $total_regle = 0.0;

    // Totaux finaux
    public $total_brut = 0.0;
    public $net_a_payer = 0.0;
    public $reste_du = 0.0;

    public function __construct() {}

    public function calculerTotaux() {
        $this->total_brut = $this->cout_hotel + $this->total_sessions + $this->total_activites;
        // Net à payer = total brut - acompte versé (ou à déduire)
        $this->net_a_payer = $this->total_brut - $this->montant_acompte;
        $this->reste_du = $this->net_a_payer - $this->total_regle;
    }
}
?>
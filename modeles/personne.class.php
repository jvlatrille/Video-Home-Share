<?php

class Personne {
    private $idPersonne;
    private $nom;
    private $prenom;
    private $dateNaiss;
    private $genre;

    public function __construct($idPersonne, $nom, $prenom, $dateNaiss, $genre) {
        $this->idPersonne = $idPersonne;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->dateNaiss = $dateNaiss;
        $this->genre = $genre;
    }

    // Getters
    public function getIdPersonne() {
        return $this->idPersonne;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getDateNaiss() {
        return $this->dateNaiss;
    }

    public function getGenre() {
        return $this->genre;
    }

    // Setters
    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    public function setDateNaiss($dateNaiss) {
        $this->dateNaiss = $dateNaiss;
    }

    public function setGenre($genre) {
        $this->genre = $genre;
    }
}

?>

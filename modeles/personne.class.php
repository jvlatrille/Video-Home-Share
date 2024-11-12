<?php

class Personne
{
    // Attributs de la classe Personne
    private ?int $idPersonne;
    private ?string $nom;
    private ?string $prenom;
    private ?string $dateNaiss;
    private ?string $genre;

    // Constructeur de la classe Personne
    public function __construct(?int $idPersonne = null, ?string $nom = null, ?string $prenom = null, ?string $dateNaiss = null, ?string $genre = null)
    {
        $this->idPersonne = $idPersonne;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->dateNaiss = $dateNaiss;
        $this->genre = $genre;
    }

    // Getters et Setters
    public function getIdPersonne(): ?int
    {
        return $this->idPersonne;
    }

    public function setIdPersonne(?int $idPersonne): void
    {
        $this->idPersonne = $idPersonne;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function getDateNaiss(): ?string
    {
        return $this->dateNaiss;
    }

    public function setDateNaiss(?string $dateNaiss): void
    {
        $this->dateNaiss = $dateNaiss;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): void
    {
        $this->genre = $genre;
    }
}

<?php

class Jeux {
    private ?int $IdJeux;
    private ?string $regle;
    private ?string $nom;

    // Constructeur
    public function __construct(?int $IdJeux = null, ?string $regle = null, ?string $nom = null)
    {
        $this->IdJeux = $IdJeux;
        $this->regle = $regle;
        $this->nom = $nom;
    }

    public function getIdJeux(): ?int
    {
        return $this->IdJeux;
    }

    public function setIdJeux($IdJeux): void
    {
        $this->IdJeux = $IdJeux;
    }

    public function getRegle(): ?string
    {
        return $this->regle;
    }

    public function setRegle($regle): void
    {
        $this->regle = $regle;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom($nom): void
    {
        $this->nom = $nom;
    }
}
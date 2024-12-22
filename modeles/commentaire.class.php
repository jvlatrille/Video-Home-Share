<?php

class Commentaire
{
    private ?int $idCom;
    private ?int $idTMDB;
    private ?string $contenu;
    private ?string $date;
    private ?int $idUtilisateur;
    private ?string $pseudo;
    private ?string $photoProfil;

    public function __construct(?int $idCom = null, ?int $idTMDB = null, ?string $contenu = null, ?string $date = null, ?int $idUtilisateur = null, ?string $pseudo = null, ?string $photoProfil = null)
    {
        $this->idCom = $idCom;
        $this->idTMDB = $idTMDB;
        $this->contenu = $contenu;
        $this->date = $date;
        $this->idUtilisateur = $idUtilisateur;
        $this->pseudo = $pseudo;
        $this->photoProfil = $photoProfil;
    }

    // Getters et Setters
    public function getIdCom(): ?int
    {
        return $this->idCom;
    }
    public function setIdCom(?int $idCom): void
    {
        $this->idCom = $idCom;
    }

    public function getIdTMDB(): ?int
    {
        return $this->idTMDB;
    }
    public function setIdTMDB(?int $idTMDB): void
    {
        $this->idTMDB = $idTMDB;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }
    public function setContenu(?string $contenu): void
    {
        $this->contenu = $contenu;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }
    public function setDate(?string $date): void
    {
        $this->date = $date;
    }
    public function getIdUtilisateur(): ?int
    {
        return $this->idUtilisateur;
    }
    public function setIdUtilisateur(?int $idUtilisateur): void
    {
        $this->idUtilisateur = $idUtilisateur;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }
    public function setPseudo(?string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    public function getPhotoProfil(): ?string
    {
        return $this->photoProfil;
    }
    public function setPhotoProfil(?string $photoProfil): void
    {
        $this->photoProfil = $photoProfil;
    }
}

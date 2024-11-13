<?php

class Utilisateur
{
    //attribut
    private ?int $idUtilisateur;
    private ?string $pseudo;
    private ?string $photoProfil;
    private ?string $banniereProfil;
    private ?string $adressMail;
    private ?string $motDePasse;
    private ?string $role;

    // Constructeur de la classe Personne
    public function __construct(?int $idUtilisateur = null, 
                                ?string $pseudo = null, 
                                ?string $photoProfil = null, 
                                ?string $banniereProfil = null, 
                                ?string $adressMail = null, 
                                ?string $motDePasse = null, 
                                ?string $role = null)
    {
        $this->idUtilisateur = $idUtilisateur;
        $this->pseudo = $pseudo;
        $this->photoProfil = $photoProfil;
        $this->banniereProfil = $banniereProfil;
        $this->adressMail = $adressMail;
        $this->motDePasse = $motDePasse;
        $this->role = $role;
    }

    /**
     * Get the value of idUtilisateur
     */
    public function getIdUtilisateur(): ?int
    {
        return $this->idUtilisateur;
    }

    /**
     * Set the value of idUtilisateur
     */
    public function setIdUtilisateur(?int $idUtilisateur): void
    {
        $this->idUtilisateur = $idUtilisateur;
    }

    /**
     * Get the value of pseudo
     */
    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    /**
     * Set the value of pseudo
     */
    public function setPseudo(?string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    /**
     * Get the value of photoProfil
     */
    public function getPhotoProfil(): ?string
    {
        return $this->photoProfil;
    }

    /**
     * Set the value of photoProfil
     */
    public function setPhotoProfil(?string $photoProfil): void
    {
        $this->photoProfil = $photoProfil;
    }

    /**
     * Get the value of banniereProfil
     */
    public function getBanniereProfil(): ?string
    {
        return $this->banniereProfil;
    }

    /**
     * Set the value of banniereProfil
     */
    public function setBanniereProfil(?string $banniereProfil): void
    {
        $this->banniereProfil = $banniereProfil;
    }

    /**
     * Get the value of adressMail
     */
    public function getAdressMail(): ?string
    {
        return $this->adressMail;
    }

    /**
     * Set the value of adressMail
     */
    public function setAdressMail(?string $adressMail): void
    {
        $this->adressMail = $adressMail;
    }

    /**
     * Get the value of motDePasse
     */
    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    /**
     * Set the value of motDePasse
     */
    public function setMotDePasse(?string $motDePasse): void
    {
        $this->motDePasse = $motDePasse;
    }

    /**
     * Get the value of role
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * Set the value of role
     */
    public function setRole(?string $role): void
    {
        $this->role = $role;
    }
}
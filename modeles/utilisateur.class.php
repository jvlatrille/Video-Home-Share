<?php

/**
 * @file utilisateur.class.php
 * @author LEVAL Noah
 * @brief Classe Utilisateur pour pouvoir instancier des utilisateurs
 * @version 1.0
 * @date 
 */

class Utilisateur
{
    //attribut
    /**
    * @brief Identifiant unique de l'utilisateur
    */
    private ?int $idUtilisateur;
    /**
    * @brief pseudonyme de l'utilisateur
    */
    private ?string $pseudo;
    /**
    * @brief chemin d'accee de la photo de profil de l'utilisateur
    */
    private ?string $photoProfil;
    /**
    * @brief chemin d'accee de la banniere de l'utilisateur
    */
    private ?string $banniereProfil;
    /**
    * @brief adresse mail de l'utilisateur
    */
    private ?string $adressMail;
    /**
    * @brief mot de passe hacher de l'utilisateur
    */
    private ?string $motDePasse;
    /**
    * @brief role de l'utilisateur
    */
    private ?string $role;
    /**
    * @brief bio de l'utilisateur
    */
    private ?string $bio = null;
    /**
    * @brief Indique si l'utilisateur peut se connecter
    */
    private ?int $valide = null;

    /**
     * @brief Constructeur de la classe Utilisateur
     * @param int|null $idUtilisateur Identifiant unique de l'utilisateur
     * @param string|null $pseudo pseudonyme de l'utilisateur
     * @param string|null $photoProfil chemin d'accee de la photo de profil de l'utilisateur
     * @param string|null $banniereProfil chemin d'accee de la banniere de l'utilisateur
     * @param string|null $adressMail adresse mail de l'utilisateur
     * @param string|null $motDePasse mot de passe hacher de l'utilisateur
     * @param string|null $role role de l'utilisateur
     * @param string|null $bio biographie de l'utilisateur
     * @param int|null $valide indique si l'utilisateur peut se connecter (1 si oui, 0 sinon)

     * @return void
     */
    // Constructeur de la classe Personne
    public function __construct(?int $idUtilisateur = null, 
                                ?string $pseudo = null, 
                                ?string $photoProfil = null, 
                                ?string $banniereProfil = null, 
                                ?string $adressMail = null, 
                                ?string $motDePasse = null, 
                                ?string $role = null,
                                ?string $bio = null,
                                ?int $valide = null)
    {
        $this->idUtilisateur = $idUtilisateur;
        $this->pseudo = $pseudo;
        $this->photoProfil = $photoProfil;
        $this->banniereProfil = $banniereProfil;
        $this->adressMail = $adressMail;
        $this->motDePasse = $motDePasse;
        $this->role = $role;
        $this->bio = $bio;
        $this->valide = $valide;
    }

    /**
     * @brief Getteur de l'dentifiant unique de l'utilisateur
     *
     * @return ?int
     */
    public function getIdUtilisateur(): ?int
    {
        return $this->idUtilisateur;
    }

    /**
     * @brief Setteur de l'identifiant unique de l'utilisateur
     * @param int idUtilisateur
     * @return void
     */
    public function setIdUtilisateur(?int $idUtilisateur): void
    {
        $this->idUtilisateur = $idUtilisateur;
    }

    /**
     * @brief Getteur du pseudonyme de l'utilisateur
     *
     * @return ?string
     */
    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

     /**
     * @brief Setteur du pseudonyme de l'utilisateur
     * @param string pseudo
     * @return void
     */
    public function setPseudo(?string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @brief Getteur du chemin d'accee de la photo de profil de l'utilisateur
     *
     * @return ?string
     */
    public function getPhotoProfil(): ?string
    {
        return $this->photoProfil;
    }

     /**
     * @brief Setteur du chemin d'accee de la photo de profil de l'utilisateur
     * @param string photoProfil
     * @return void
     */
    public function setPhotoProfil(?string $photoProfil): void
    {
        $this->photoProfil = $photoProfil;
    }

    /**
     * @brief Getteur du chemin d'accee de la banniere de l'utilisateur
     *
     * @return ?string
     */
    public function getBanniereProfil(): ?string
    {
        return $this->banniereProfil;
    }

    /**
     * @brief Setteur du chemin d'accee de la banniere de l'utilisateur
     * @param string banniereProfil
     * @return void
     */
    public function setBanniereProfil(?string $banniereProfil): void
    {
        $this->banniereProfil = $banniereProfil;
    }

    /**
     * @brief Getteur de l'adresse mail de l'utilisateur
     *
     * @return ?string
     */
    public function getAdressMail(): ?string
    {
        return $this->adressMail;
    }

    /**
     * @brief Setteur de l'adresse mail de l'utilisateur
     * @param string adressMail
     * @return void
     */
    public function setAdressMail(?string $adressMail): void
    {
        $this->adressMail = $adressMail;
    }

    /**
     * @brief Getteur du mot de passe hacher de l'utilisateur
     *
     * @return ?string
     */
    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    /**
     * @brief Setteur du mot de passe hacher de l'utilisateur
     * @param string motDePasse
     * @return void
     */
    public function setMotDePasse(?string $motDePasse): void
    {
        $this->motDePasse = $motDePasse;
    }

    /**
     * @brief Getteur du role de l'utilisateur
     *
     * @return ?string
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @brief Setteur du role de l'utilisateur
     * @param string role
     * @return void
     */
    public function setRole(?string $role): void
    {
        $this->role = $role;
    }

    /**
     * @brief Getteur de la bio de l'utilisateur
     *
     * @return ?string
     */
    public function getBio(): ?string
    {
        return $this->bio;
    }

    /**
     * @brief Setteur de la bio de l'utilisateur

     * @param string role
     * @return void
     */
    public function setBio(?string $bio): void
    {
        $this->bio = $bio;
    }

    /**
     * @brief Getteur de la validité de l'utilisateur
     *
     * @return ?int
     */
    public function getValide(): ?int
    {
        return $this->valide;
    }

    /**
     * @brief Setteur de la validité de l'utilisateur

     * @param int valide
     * @return void
     */
    public function setValide(?int $valide): void
    {
        $this->valide = $valide;
    }
}
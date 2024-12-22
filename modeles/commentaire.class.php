<?php
/**
 * @file commentaire.class.php
 * @author VINET LATRILLE Jules
 * @brief Classe Commentaire pour représenter les commentaires des utilisateurs
 * @version 1.0
 * @date 2024-12-22
 */

class Commentaire
{
    /**
     * @brief Identifiant unique du commentaire
     */
    private ?int $idCom;

    /**
     * @brief Identifiant du film associé (TMDB)
     */
    private ?int $idTMDB;

    /**
     * @brief Contenu du commentaire
     */
    private ?string $contenu;

    /**
     * @brief Date du commentaire
     */
    private ?string $date;

    /**
     * @brief Identifiant de l'utilisateur ayant laissé le commentaire
     */
    private ?int $idUtilisateur;

    /**
     * @brief Pseudo de l'utilisateur
     */
    private ?string $pseudo;

    /**
     * @brief Chemin de la photo de profil de l'utilisateur
     */
    private ?string $photoProfil;

    /**
     * @brief Constructeur de la classe Commentaire
     * @param int|null $idCom Identifiant du commentaire
     * @param int|null $idTMDB Identifiant du film associé
     * @param string|null $contenu Contenu du commentaire
     * @param string|null $date Date du commentaire
     * @param int|null $idUtilisateur Identifiant de l'utilisateur
     * @param string|null $pseudo Pseudo de l'utilisateur
     * @param string|null $photoProfil Photo de profil de l'utilisateur
     */
    public function __construct(
        ?int $idCom = null,
        ?int $idTMDB = null,
        ?string $contenu = null,
        ?string $date = null,
        ?int $idUtilisateur = null,
        ?string $pseudo = null,
        ?string $photoProfil = null
    ) {
        $this->idCom = $idCom;
        $this->idTMDB = $idTMDB;
        $this->contenu = $contenu;
        $this->date = $date;
        $this->idUtilisateur = $idUtilisateur;
        $this->pseudo = $pseudo;
        $this->photoProfil = $photoProfil;
    }

    /**
     * @brief Retourne l'identifiant du commentaire
     * @return int|null
     */
    public function getIdCom(): ?int
    {
        return $this->idCom;
    }

    /**
     * @brief Modifie l'identifiant du commentaire
     * @param int|null $idCom
     */
    public function setIdCom(?int $idCom): void
    {
        $this->idCom = $idCom;
    }

    /**
     * @brief Retourne l'identifiant du film associé (TMDB)
     * @return int|null
     */
    public function getIdTMDB(): ?int
    {
        return $this->idTMDB;
    }

    /**
     * @brief Modifie l'identifiant du film associé
     * @param int|null $idTMDB
     */
    public function setIdTMDB(?int $idTMDB): void
    {
        $this->idTMDB = $idTMDB;
    }

    /**
     * @brief Retourne le contenu du commentaire
     * @return string|null
     */
    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    /**
     * @brief Modifie le contenu du commentaire
     * @param string|null $contenu
     */
    public function setContenu(?string $contenu): void
    {
        $this->contenu = $contenu;
    }

    /**
     * @brief Retourne la date du commentaire
     * @return string|null
     */
    public function getDate(): ?string
    {
        return $this->date;
    }

    /**
     * @brief Modifie la date du commentaire
     * @param string|null $date
     */
    public function setDate(?string $date): void
    {
        $this->date = $date;
    }

    /**
     * @brief Retourne l'identifiant de l'utilisateur
     * @return int|null
     */
    public function getIdUtilisateur(): ?int
    {
        return $this->idUtilisateur;
    }

    /**
     * @brief Modifie l'identifiant de l'utilisateur
     * @param int|null $idUtilisateur
     */
    public function setIdUtilisateur(?int $idUtilisateur): void
    {
        $this->idUtilisateur = $idUtilisateur;
    }

    /**
     * @brief Retourne le pseudo de l'utilisateur
     * @return string|null
     */
    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    /**
     * @brief Modifie le pseudo de l'utilisateur
     * @param string|null $pseudo
     */
    public function setPseudo(?string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @brief Retourne le chemin de la photo de profil de l'utilisateur
     * @return string|null
     */
    public function getPhotoProfil(): ?string
    {
        return $this->photoProfil;
    }

    /**
     * @brief Modifie le chemin de la photo de profil de l'utilisateur
     * @param string|null $photoProfil
     */
    public function setPhotoProfil(?string $photoProfil): void
    {
        $this->photoProfil = $photoProfil;
    }
}

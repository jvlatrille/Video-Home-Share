<?php

/**
 * @file personne.class.php
 * @author VINET LATRILLE Jules
 * @brief Classe Personne pour représenter une personne
 * @details Cette classe permet de représenter une personne avec ses attributs (idPersonne, nom, prénom, dateNaiss).
 * @version 1.0
 * @date : 17/11/2024
 */
class Personne
{
    /**
     * @brief Identifiant de la personne
     * @var int|null
     */
    private ?int $idPersonne;

    /**
     * @brief Nom de la personne
     * @var string|null
     */
    private ?string $nom;

    /**
     * @brief Prénom de la personne
     * @var string|null
     */
    private ?string $prenom;

    /**
     * @brief Date de naissance de la personne
     * @var string|null
     */
    private ?string $dateNaiss;

    /**
     * @brief Constructeur de la classe Personne
     * @param int|null $idPersonne Identifiant de la personne
     * @param string|null $nom Nom de la personne
     * @param string|null $prenom Prénom de la personne
     * @param string|null $dateNaiss Date de naissance de la personne
     */
    public function __construct(?int $idPersonne = null, ?string $nom = null, ?string $prenom = null, ?string $dateNaiss = null)
    {
        $this->idPersonne = $idPersonne;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->dateNaiss = $dateNaiss;
    }

    /**
     * @brief Retourne l'identifiant de la personne
     * @return int|null Identifiant de la personne
     */
    public function getIdPersonne(): ?int
    {
        return $this->idPersonne;
    }

    /**
     * @brief Modifie l'identifiant de la personne
     * @param int|null $idPersonne Identifiant de la personne
     */
    public function setIdPersonne(?int $idPersonne): void
    {
        $this->idPersonne = $idPersonne;
    }

    /**
     * @brief Retourne le nom de la personne
     * @return string|null Nom de la personne
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @brief Modifie le nom de la personne
     * @param string|null $nom Nom de la personne
     */
    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @brief Retourne le prénom de la personne
     * @return string|null Prénom de la personne
     */
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    /**
     * @brief Modifie le prénom de la personne
     * @param string|null $prenom Prénom de la personne
     */
    public function setPrenom(?string $prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @brief Retourne la date de naissance de la personne
     * @return string|null Date de naissance de la personne
     */
    public function getDateNaiss(): ?string
    {
        return $this->dateNaiss;
    }

    /**
     * @brief Modifie la date de naissance de la personne
     * @param string|null $dateNaiss Date de naissance de la personne
     */
    public function setDateNaiss(?string $dateNaiss): void
    {
        $this->dateNaiss = $dateNaiss;
    }
}

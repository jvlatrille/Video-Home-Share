<?php

/**
 * @file oa.class.php
 * @author Thibault CHIPY, VINET LATRILLE Jules
 * @brief Classe OA pour représenter une œuvre audiovisuelle
 * @details Cette classe permet de représenter une œuvre audiovisuelle avec ses attributs principaux.
 * @version 2.0
 * @date 2024-12-22
 */

class OA
{
    /** @brief Identifiant de l'œuvre audiovisuelle */
    private ?int $idOa;

    /** @brief Nom de l'œuvre audiovisuelle */
    private ?string $nom;

    /** @brief Note de l'œuvre audiovisuelle */
    private ?float $note;

    /** @brief Type de l'œuvre audiovisuelle */
    private ?string $type;

    /** @brief Description de l'œuvre audiovisuelle */
    private ?string $description;

    /** @brief Date de sortie de l'œuvre audiovisuelle */
    private ?string $dateSortie;

    /** @brief Version originale de l'œuvre audiovisuelle */
    private ?string $vo;

    /** @brief Durée de l'œuvre audiovisuelle */
    private ?int $duree;

    /** @brief Genres de l'œuvre audiovisuelle */
    private ?array $genres = [];

    /** @brief Collection de l'œuvre audiovisuelle */
    private ?string $collection;

    /** @brief Lien vers la couverture de l'œuvre audiovisuelle */
    private ?string $posterPath;

    /** @brief Liste des participants de l'œuvre audiovisuelle */
    private ?array $participants = [];

    /** @brief Nom du producteur de l'œuvre audiovisuelle */
    private ?string $producteur;

    /** @brief Nombre de saison */
    private ?int $nbSaison;

    /** @brief Nombre d'épisode */
    private ?int $nbEpisode;



    /**
     * @brief Constructeur de la classe OA
     * @param int|null $id Identifiant de l'œuvre audiovisuelle
     * @param string|null $nom Nom de l'œuvre audiovisuelle
     * @param float|null $note Note de l'œuvre audiovisuelle
     * @param string|null $type Type de l'œuvre audiovisuelle
     * @param string|null $description Description de l'œuvre audiovisuelle
     * @param string|null $dateSortie Date de sortie de l'œuvre audiovisuelle
     * @param string|null $vo Version originale de l'œuvre audiovisuelle
     * @param int|null $duree Durée de l'œuvre audiovisuelle
     * @param array|null $genres Genres de l'œuvre audiovisuelle
     * @param string|null $collection Collection de l'œuvre audiovisuelle
     * @param string|null $posterPath Lien de la couverture de l'œuvre audiovisuelle
     * @param array|null $participants Liste des participants
     * @param string|null $producteur Nom du producteur
     * @param int|null $nbSaison Nombre de saison
     * @param int|null $nbEpisode Nombre d'épisode
     */
    public function __construct(
        ?int $id = null,
        ?string $nom = null,
        ?float $note = null,
        ?string $type = null,
        ?string $description = null,
        ?string $dateSortie = null,
        ?string $vo = null,
        ?int $duree = null,
        ?array $genres = null,
        ?string $collection = null,
        ?string $posterPath = null,
        ?array $participants = null,
        ?string $producteur = null,
        ?int $nbSaison = null,
        ?int $nbEpisode = null
    ) {
        $this->idOa = $id;
        $this->nom = $nom;
        $this->note = $note;
        $this->type = $type;
        $this->description = $description;
        $this->dateSortie = $dateSortie;
        $this->vo = $vo;
        $this->duree = $duree;
        $this->genres = $genres;
        $this->collection = $collection;
        $this->posterPath = $posterPath;
        $this->participants = $participants;
        $this->producteur = $producteur;
        $this->nbSaison = $nbSaison;
        $this->nbEpisode = $nbEpisode;
    }

    // Getters et Setters

    public function getIdOa(): ?int
    {
        return $this->idOa;
    }
    public function setIdOa(?int $id): void
    {
        $this->idOa = $id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }
    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }
    public function setNote(?float $note): void
    {
        $this->note = $note;
    }

    public function getType(): ?string
    {
        return $this->type;
    }
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getDateSortie(): ?string
    {
        return $this->dateSortie;
    }
    public function setDateSortie(?string $dateSortie): void
    {
        $this->dateSortie = $dateSortie;
    }

    public function getVo(): ?string
    {
        return $this->vo;
    }
    public function setVo(?string $vo): void
    {
        $this->vo = $vo;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }
    public function setDuree(?int $duree): void
    {
        $this->duree = $duree;
    }

    public function getGenres(): ?array
    {
        return $this->genres;
    }
    public function setGenres(?array $genres): void
    {
        $this->genres = $genres;
    }

    public function getCollection(): ?string
    {
        return $this->collection;
    }
    public function setCollection(?string $collection): void
    {
        $this->collection = $collection;
    }

    public function getPosterPath(): ?string
    {
        return $this->posterPath;
    }
    public function setPosterPath(?string $posterPath): void
    {
        $this->posterPath = $posterPath;
    }

    public function getParticipants(): ?array
    {
        return $this->participants;
    }
    public function setParticipants(?array $participants): void
    {
        $this->participants = $participants;
    }

    public function getProducteur(): ?string
    {
        return $this->producteur;
    }
    public function setProducteur(?string $producteur): void
    {
        $this->producteur = $producteur;
    }

    public function getNbSaison(): ?int
    {
        return $this->nbSaison;
    }

    public function setNbSaison(?int $nbSaison): void
    {
        $this->nbSaison = $nbSaison;
    }

    public function getNbEpisode(): ?int
    {
        return $this->nbEpisode;
    }

    public function setNbEpisode(?int $nbEpisode): void
    {
        $this->nbEpisode = $nbEpisode;
    }
    
}

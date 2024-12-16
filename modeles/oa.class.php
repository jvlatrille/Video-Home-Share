<?php

/**
 * @file oa.class.php
 * @author Thibault CHIPY
 * @brief classe OA pour représenter une oeuvre audiovisuelle
 * @details Cette OA classe permet de représenter une oeuvre audiovisuelle avec 
 * ses attributs(id, nom, note, type, description, dateSortie, vo, duree)
 * @version 2.0
 * @date : 13/11/2024
 */

class OA
{
    //Attributs d'une oeuvre audiovisuelle
    /**
     * @brief Identifiant de l'oeuvre audiovisuelle
     */
    private ?int $idOa;
    /**
     * @brief Nom de l'oeuvre audiovisuelle
     */
    private ?string $nom;
    /**
     * @brief Note de l'oeuvre audiovisuelle
     */
    private ?float $note;
    /**
     * @brief Type de l'oeuvre audiovisuelle
     */
    private ?string $type;
    /**
     * @brief Description de l'oeuvre audiovisuelle
     */
    private ?string $description;
    /**
     * @brief Date de sortie de l'oeuvre audiovisuelle
     */
    private ?string $dateSortie;
    /**
     * @brief Version originale de l'oeuvre audiovisuelle
     */
    private ?string $vo;

    /**
     * @brief Durée de l'oeuvre audiovisuelle
     */
    private ?int $duree;

    /**
     * @brief Genres de l'oeuvre audiovisuelle 
     *
     * @var string|null
     */
    private ?array $genres = [];

    /**
     * @brief Collection de l'oeuvre audiovisuelle
     * 
     * @var string|null
     */
    private ?string $collection;

    /**
     * @brief Lien vers la couverture de l'oeuvre audiovisuelle
     * 
     * * @var string|null
     */
    private ?string $posterPath;


    /**
     * @brief Constructeur de la classe OA
     * @param int|null $idOa : identifiant de l'oeuvre audiovisuelle
     * @param string|null $nom : nom de l'oeuvre audiovisuelle
     * @param float|null $note : note de l'oeuvre audiovisuelle
     * @param string|null $type : type de l'oeuvre audiovisuelle
     * @param string|null $description : description de l'oeuvre audiovisuelle
     * @param string|null $dateSortie : date de sortie de l'oeuvre audiovisuelle
     * @param string|null $vo : version originale de l'oeuvre audiovisuelle
     * @param int|null $duree : durée de l'oeuvre audiovisuelle
     * @param array|null $genres : genres de l'oeuvre audiovisuelle
     * @param string|null $collection : Collection de l'oeuvre audiovisuelle
     * @param string|null $posterPath : Collection de l'oeuvre audiovisuelle
     */
    public function __construct(?int $id = null, ?string $nom = null, ?float $note = null, ?string $type = null, ?string $description = null, ?string $dateSortie = null, ?string $vo = null, ?int $duree = null, ?array $genres = null, ?string $collection = null, ?string $posterPath = null)
    {
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
    }

    //Getters et setters de la classe OA

    /**
     * @brief Retourne l'id de l'oeuvre audiovisuelle OA 
     *
     * @return integer|null identifiant de l'oeuvre audiovisuelle
     */
    public function getIdOa(): ?int
    {
        return $this->idOa;
    }

    /**
     * @brief Modifie l'id de l'oeuvre audiovisuelle OA
     *
     * @param integer|null $id : identifiant de l'oeuvre audiovisuelle
     */
    public function setIdOa(?int $id): void
    {
        $this->idOa = $id;
    }

    /**
     * @brief Retourne le nom de l'oeuvre audiovisuelle OA
     *
     * @return string|null nom de l'oeuvre audiovisuelle
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @brief Modifie le nom de l'oeuvre audiovisuelle OA
     *
     * @param string|null $nom : nom de l'oeuvre audiovisuelle
     */
    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @brief Retourne la note de l'oeuvre audiovisuelle OA
     *
     * @return integer|null note de l'oeuvre audiovisuelle
     */
    public function getNote(): ?float
    {
        return $this->note;
    }

    /**
     * @brief Modifie la note de l'oeuvre audiovisuelle OA
     *
     * @param integer|null $note : note de l'oeuvre audiovisuelle
     */
    public function setNote(?float $note): void
    {
        $this->note = $note;
    }

    /**
     * @brief Retourne le type de l'oeuvre audiovisuelle OA
     *
     * @return string|null type de l'oeuvre audiovisuelle
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @brief Modifie le type de l'oeuvre audiovisuelle OA
     *
     * @param string|null $type : type de l'oeuvre audiovisuelle
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @brief Retourne la description de l'oeuvre audiovisuelle OA
     *
     * @return string|null description de l'oeuvre audiovisuelle
     */

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @brief Modifie la description de l'oeuvre audiovisuelle OA
     *
     * @param string|null $description : description de l'oeuvre audiovisuelle
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @brief Retourne la date de sortie de l'oeuvre audiovisuelle OA
     *
     * @return string|null date de sortie de l'oeuvre audiovisuelle
     */
    public function getDateSortie(): ?string
    {
        return $this->dateSortie;
    }

    /**
     * @brief Modifie la date de sortie de l'oeuvre audiovisuelle OA
     *
     * @param string|null $dateSortie : date de sortie de l'oeuvre audiovisuelle
     */
    public function setDateSortie(?string $dateSortie): void
    {
        $this->dateSortie = $dateSortie;
    }

    /**
     * @brief Retourne la version originale de l'oeuvre audiovisuelle OA
     *
     * @return string|null version originale de l'oeuvre audiovisuelle
     */
    public function getVo(): ?string
    {
        return $this->vo;
    }

    /**
     * @brief Modifie la version originale de l'oeuvre audiovisuelle OA
     *
     * @param string|null $vo : version originale de l'oeuvre audiovisuelle
     */
    public function setVo(?string $vo): void
    {
        $this->vo = $vo;
    }


    /**
     * @brief Retourne la durée de l'oeuvre audiovisuelle OA
     *
     * @return integer|null durée de l'oeuvre audiovisuelle
     */

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    /**
     * @brief Modifie la durée de l'oeuvre audiovisuelle OA
     *
     * @param integer|null $duree : durée de l'oeuvre audiovisuelle
     */
    public function setDuree(?int $duree): void
    {
        $this->duree = $duree;
    }

    /**
     * @brief Retourne les genres de l'oeuvre audiovisuelle OA
     *
     * @return array|null genres de l'oeuvre audiovisuelle
     */
    public function getGenres(): ?array
    {
        return $this->genres;
    }

    /**
     * @brief Modifie les genres de l'oeuvre audiovisuelle OA
     * 
     * @param array|null $genres : genres de l'oeuvre audiovisuelle
     * 
     */

    public function setGenres(?array $genres): void
    {
        $this->genres = $genres;
    }

    /**
     * @brief Retourne la collection de l'oeuvre audiovisuelle OA
     *
     * @return string|null collection de l'oeuvre audiovisuelle
     */
    public function getCollection(): ?string
    {
        return $this->collection;
    }

    /**
     * @brief Modifie la collection de l'oeuvre audiovisuelle OA
     *
     * @param string|null $collection : collection de l'oeuvre audiovisuelle
     */
    public function setCollection(?string $collection): void
    {
        $this->collection = $collection;
    }

    /**
     * @brief Retourne le chemin de la couverture de l'oeuvre audiovisuelle OA 
     *
     * @return integer|null lien de la couverture de l'oeuvre audiovisuelle
     */
    public function getposterPath(): ?string
    {
        return $this->posterPath;
    }

    /**
     * @brief Modifie le chemin de la couverture de l'oeuvre audiovisuelle OA 
     *
     * @param integer|null $path : lien de la couverture de l'oeuvre audiovisuelle
     */
    public function setposterPath(?int $path): void
    {
        $this->posterPath = $path;
    }
}

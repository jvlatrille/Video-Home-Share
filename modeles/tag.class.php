<?php

/**
 * @file tag.class.php
 * @author Thibault CHIPY
 * @brief classe Tag pour représenter les tags des oeuvres qui sont les genres
 * @details Cette OA classe permet de représenter un tag avec ses attributs(id, nom)
 * @version 1.0
 * @date : 13/11/2024
 */

class Tag{

    private ?int $idTag;
    private ?string $nom;

    /**
     * @brief Constructeur de la classe Tag
     * @param int|null $idTag : identifiant du tag
     * @param string|null $nom : nom du tag
     */
    public function __construct(?int $id=null, ?string $nom=null){
        $this->idTag = $id;
        $this->nom = $nom;
    }

    public function getIdTag(): ?int{
        return $this->idTag;
    }

    public function setIdTag(?int $id): void{
        $this->idTag = $id;
    }


    public function getNom(): ?string{
        return $this->nom;
    }

    public function setNom(?string $nom): void{
        $this->nom = $nom;
    }


}
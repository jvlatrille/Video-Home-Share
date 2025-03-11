<?php

/**
 * Une classe pour représenter un jeu
 */
class Jeux {
    /**
     * Attribut de la classe Jeu
     *
     * @var integer|null $idJeux L'identifiant du jeu 
     * @var string|null $regle La règle associée au jeu
     * @var string|null $nom Le nom du  jeu
     * @var string|null $nom L'image du  jeu
     */
    private ?int $idJeux;
    private ?string $regle;
    private ?string $nom;
    private ?string $image;

    /**
     * Fonction qui permet de construire 
     *
     * @param integer|null $idJeux L'identifiant du jeu à construire
     * @param string|null $regle La règle du jeu à construire
     * @param string|null $nom Le nom du jeu à construire
     * @param string|null $nom L'image du jeu à construire
     */
    public function __construct(?int $idJeux = null, ?string $regle = null, ?string $nom = null, ?string $image = null, )
    {
        $this->idJeux = $idJeux;
        $this->regle = $regle;
        $this->nom = $nom;
        $this->image = $image;
    }

    /**
     * Fonction qui permet de récupérer l'identifiant du jeu
     *
     * @return integer|null L'identifiant du jeu actuel
     */
    public function getIdJeux(): ?int
    {
        return $this->idJeux;
    }

    /**
     * Fonction qui permet de modifier l'identifiant du jeu
     *
     * @param integer|null $idJeux 
     * @return void
     */
    public function setIdJeux(?int $idJeux): void
    {
        $this->idJeux = $idJeux;
    }

    /**
     * Fonction qui permet de récupérer la règle du jeu
     *
     * @return string|null
     */
    public function getRegle(): ?string
    {
        return $this->regle;
    }

    /**
     * Fonction qui permet de modifier la règle du jeu
     *
     * @param string|null $regle
     * @return void
     */
    public function setRegle(?string $regle): void
    {
        $this->regle = $regle;
    }

    /**
     * Fonction qui permet de récupérer le nom du jeu
     *
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * Fonction qui permet de modifier le nom du jeu
     *
     * @param string|null $nom
     * @return void
     */
    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * Fonction qui permet de récupérer l'image' du jeu
     *
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Fonction qui permet de modifier l'image du jeu
     *
     * @param string|null $image
     * @return void
     */
    public function setImage(?string $image): void
    {
        $this->image = $image;
    }
}
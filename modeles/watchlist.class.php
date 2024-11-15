<?php
/**
 * @file watchlist.class.php 
 * @author Thibault CHIPY 
 * @brief Classe WatchList pour gérer les watchlists 
 * @details Cette classe permet de gérer les watchlists
 * avec leurs attributs (idWatchlist, titre, genre, description, visible, listeOeuvres) et
 * les méthodes pour les manipuler
 * 
 * @version 1.0
 * @date 13/11/2020
*/
class WatchList{
    //Attributs d'une watchlist

    /**
     * @brief Identifiant de la watchlist 
     *
     * @var integer|null 
     */
    private ?int $idWatchlist;
    /**
     * @brief Titre de la watchlist 
     *
     * @var string|null
     */
    private ?string $titre;
    /**
     * @brief Genre de la watchlist 
     *
     * @var string|null
     */
    private ?string $genre;
    /**
     * @brief Description de la watchlist 
     *
     * @var string|null
     */
    private ?string $description;
    /**
     * @brief Visibilité de la watchlist 
     *
     * @var bool|null
     */
    private ?bool $visible;
    /**
     * @brief Liste des oeuvres de la watchlist 
     *
     * @var array|null
     */
    private ?array $listeOeuvres;

    //Constructeur de la classe WatchList

    /**
     * @brief Constructeur de la classe WatchList
     * @param integer|null $idWatchList : identifiant de la watchlist
     * @param string|null $titre : titre de la watchlist
     * @param string|null $genre : genre de la watchlist
     * @param string|null $description : description de la watchlist
     * @param bool|null $visible : visibilité de la watchlist
     * @param array|null $listeOeuvres : liste des oeuvres de la watchlist
     */
    public function __construct(?int $idWatchList=null, ?string $titre=null, ?string $genre=null, ?string $description=null, ?bool $visible=null, ?array $listeOeuvres=null){
        $this->idWatchlist = $idWatchList;
        $this->titre = $titre;
        $this->genre = $genre;
        $this->description = $description;
        $this->visible = $visible;
        $this->listeOeuvres = $listeOeuvres;
    }

    //Getters et setters de la classe WatchList

    /**
     * @brief Retourne l'id de la watchlist WatchList
     *
     * @return integer|null identifiant de la watchlist
     */
    public function getIdWatchlist(): ?int{
        return $this->idWatchlist;
    }

    /**
     * @brief Modifie l'id de la watchlist WatchList
     *
     * @param integer|null $idWatchList : identifiant de la watchlist
     * @return void
     */
    public function setIdWatchlist(?int $idWatchList): void{
        $this->idWatchlist = $idWatchList;
    }

    /**
     * @brief Retourne le titre de la watchlist WatchList
     *
     * @return string|null titre de la watchlist
     */
    public function getTitre(): ?string{
        return $this->titre;
    }

    /**
     * @brief Modifie le titre de la watchlist WatchList
     *
     * @param string|null $titre : titre de la watchlist
     * @return void
     */
    public function setTitre(?string $titre): void{
        $this->titre = $titre;
    }

    /**
     * @brief Retourne le genre de la watchlist WatchList
     *
     * @return string|null genre de la watchlist
     */
    public function getGenre(): ?string{
        return $this->genre;
    }

    /**
     * @brief Modifie le genre de la watchlist WatchList
     *
     * @param string|null $genre : genre de la watchlist
     * @return void
     */
    public function setGenre(?string $genre): void{
        $this->genre = $genre;
    }

    /**
     * @brief Retourne la description de la watchlist WatchList
     *
     * @return string|null description de la watchlist
     */
    public function getDescription(): ?string{
        return $this->description;
    }

    /**
     * @brief Modifie la description de la watchlist WatchList
     *
     * @param string|null $description : description de la watchlist
     * @return void
     */
    public function setDescription(?string $description): void{
        $this->description = $description;
    }

    /**
     * @brief Retourne la visibilité de la watchlist WatchList
     *
     * @return bool|null visibilité de la watchlist
     */
    public function getVisible(): ?bool{
        return $this->visible;
    }

    /**
     * @brief Modifie la visibilité de la watchlist WatchList
     *
     * @param bool|null $visible : visibilité de la watchlist
     * @return void
     */
    public function setVisible(?bool $visible): void{
        $this->visible = $visible;
    }

    /**
     * @brief Retourne la liste des oeuvres de la watchlist WatchList
     *
     * @return array|null liste des oeuvres de la watchlist
     */
    public function getListeOeuvres(): ?array{
        return $this->listeOeuvres;
    }

    /**
     * @brief Modifie la liste des oeuvres de la watchlist WatchList
     *
     * @param array|null $listeOeuvres : liste des oeuvres de la watchlist
     * @return void
     */
    public function setListeOeuvres(?array $listeOeuvres): void{
        $this->listeOeuvres = $listeOeuvres;
    }

    /**
     * @brief Ajoute une oeuvre à la liste des oeuvres de la watchlist
     *
     * @param OA $oa : oeuvre audiovisuelle à ajouter
     * @return void
     */
    public function addOeuvre(OA $oa){
        $this->listeOeuvres[] = $oa;
    }

    /**
     * @brief Supprime une oeuvre de la liste des oeuvres de la watchlist
     *
     * @param OA $oa : oeuvre audiovisuelle à supprimer
     * @return void
     */
    public function removeOeuvre(OA $oa){
        $key = array_search($oa, $this->listeOeuvres);
        if($key !== false){
            unset($this->listeOeuvres[$key]);
        }
    }
    

}
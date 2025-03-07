<?php
/**
 * @file watchlist.class.php 
 * @author Thibault CHIPY 
 * @brief Classe WatchList pour gérer les watchlists 
 * @details Cette classe permet de gérer les watchlists
 * avec leurs attributs (idWatchlist, titre, genre, description, visible, listeOeuvres) et
 * les méthodes pour les manipuler
 * 
 * @version 2.0
 * @date 29/12/2024
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
     * @brief les id de TMBD des oeuvres de la watchlist
     *
     * @var string|null
     */
    private ?string $idTMDB;

    /**
     * @brief Tableau des oeuvres de la watchlist
     *
     * @var array|null
     */
    private ?array $listeOeuvres;

    /**
     * @brief Id de l'utilisateur qui a créé la watchlist
     *@var int|null
     */
    private ?int $idUtilisateur;

    /**
     * @brief Constructeur de la classe WatchList
     * @param integer|null $idWatchList : identifiant de la watchlist
     * @param string|null $titre : titre de la watchlist
     * @param string|null $genre : genre de la watchlist
     * @param string|null $description : description de la watchlist
     * @param bool|null $visible : visibilité de la watchlist
     * @param array|null $listeOeuvres : liste des oeuvres de la watchlist
     */
    public function __construct(?int $idWatchList=null, ?string $titre=null, ?string $genre=null, ?string $description=null, ?bool $visible=null, ?string $idTMDB=null, ?array $listeOeuvres=null,?int $idUtilisateur=null){
        $this->idWatchlist = $idWatchList;
        $this->titre = $titre;
        $this->genre = $genre;
        $this->description = $description;
        $this->visible = $visible;
        $this->idTMDB = $idTMDB;
        $this->listeOeuvres = $listeOeuvres;
        $this->idUtilisateur = $idUtilisateur;
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
     * @brief Retourne la liste des ID TMDB de la watchlist WatchList
     *
     * @return string|null liste des id TMDB de la watchlist
     */
    public function getIdTMDB(): ?string{
        return $this->idTMDB;
    }

    /**
     * @brief Modifie la liste des Id TMBD de la watchlist WatchList
     *
     * @param string|null $idTMBD : liste des ID TMDB de la watchlist
     * @return void
     */
    public function setIdTMDB(?string $idTMBD): void{
        $this->idTMDB = $idTMBD;
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

    /**
     * @brief Retourne l'id de l'utilisateur qui a créé la watchlist
     *
     * @return int|null id de l'utilisateur
     */
    public function getIdUtilisateur(): ?int{
        return $this->idUtilisateur;
    }

    /**
     * @brief Modifie l'id de l'utilisateur qui a créé la watchlist
     *
     * @param int|null $idUtilisateur : id de l'utilisateur
     * @return void
     */
    public function setIdUtilisateur(?int $idUtilisateur): void{
        $this->idUtilisateur = $idUtilisateur;
    }
    

}
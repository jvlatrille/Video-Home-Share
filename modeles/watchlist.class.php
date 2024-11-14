<?php
class WatchList{
    //Attributs d'une watchlist
    private ?int $idWatchlist;
    private ?string $titre;
    private ?string $genre;
    private ?string $description;
    private ?bool $visible;
    private ?array $listeOeuvres;

    //Constructeur de la classe WatchList
    public function __construct(?int $idWatchList=null, ?string $titre=null, ?string $genre=null, ?string $description=null, ?bool $visible=null, ?array $listeOeuvres=null){
        $this->idWatchlist = $idWatchList;
        $this->titre = $titre;
        $this->genre = $genre;
        $this->description = $description;
        $this->visible = $visible;
        $this->listeOeuvres = $listeOeuvres;
    }

    //Getters et setters de la classe WatchList
    public function getIdWatchlist(): ?int{
        return $this->idWatchlist;
    }

    public function setIdWatchlist(?int $idWatchList): void{
        $this->idWatchlist = $idWatchList;
    }

    public function getTitre(): ?string{
        return $this->titre;
    }

    public function setTitre(?string $titre): void{
        $this->titre = $titre;
    }

    public function getGenre(): ?string{
        return $this->genre;
    }

    public function setGenre(?string $genre): void{
        $this->genre = $genre;
    }

    public function getDescription(): ?string{
        return $this->description;
    }

    public function setDescription(?string $description): void{
        $this->description = $description;
    }

    public function getVisible(): ?bool{
        return $this->visible;
    }

    public function setVisible(?bool $visible): void{
        $this->visible = $visible;
    }

    public function getListeOeuvres(): ?array{
        return $this->listeOeuvres;
    }

    public function setListeOeuvres(?array $listeOeuvres): void{
        $this->listeOeuvres = $listeOeuvres;
    }

    public function addOeuvre(OA $oa){
        $this->listeOeuvres[] = $oa;
    }

    public function removeOeuvre(OA $oa){
        $key = array_search($oa, $this->listeOeuvres);
        if($key !== false){
            unset($this->listeOeuvres[$key]);
        }
    }
    

}
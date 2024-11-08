<?php
class WatchList{
    //Attributs d'une watchlist
    private ?int $idWatchlist;
    private ?string $titre;
    private ?string $genre;
    private ?string $description;
    private ?bool $visible;

    //Constructeur de la classe WatchList
    public function __construct(?int $idWatchList=null, ?string $titre=null, ?string $genre=null, ?string $description=null, ?bool $visible=null){
        $this->idWatchlist = $idWatchList;
        $this->titre = $titre;
        $this->genre = $genre;
        $this->description = $description;
        $this->visible = $visible;
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


    

}
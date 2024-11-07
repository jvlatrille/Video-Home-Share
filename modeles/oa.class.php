<?php

class OA{
    //Attributs d'une oeuvre audiovisuelle
    private ?int $id;
    private ?string $nom;
    private ?int $note;
    private ?string $type;
    private ?string $description;
    private ?date $dateSortie;
    private ?string $vo;
    private ?int $limiteAge;
    private ?int $duree;

    //Constructeur de la classe OA
    public function __construct(?int $id, ?string $nom, ?int $note, ?string $type, ?string $description, ?date $dateSortie, ?string $vo, ?int $limiteAge, ?int $duree){
        $this->id = $id;
        $this->nom = $nom;
        $this->note = $note;
        $this->type = $type;
        $this->description = $description;
        $this->dateSortie = $dateSortie;
        $this->vo = $vo;
        $this->limiteAge = $limiteAge;
        $this->duree = $duree;
    }

    //Getters et setters de la classe OA
    public function getId(): ?int{
        return $this->id;
    }

    public function setId(?int $id): void{
        $this->id = $id;
    }

    public function getNom(): ?string{
        return $this->nom;
    }

    public function setNom(?string $nom): void{
        $this->nom = $nom;
    }

    public function getNote(): ?int{
        return $this->note;
    }

    public function setNote(?int $note): void{
        $this->note = $note;
    }

    public function getType(): ?string{
        return $this->type;
    }

    public function setType(?string $type): void{
        $this->type = $type;
    }

    public function getDescription(): ?string{
        return $this->description;
    }

    public function setDescription(?string $description): void{
        $this->description = $description;
    }

    public function getDateSortie(): ?date{
        return $this->dateSortie;
    }

    public function setDateSortie(?date $dateSortie): void{
        $this->dateSortie = $dateSortie;
    }



}

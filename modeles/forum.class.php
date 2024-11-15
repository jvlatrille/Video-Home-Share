<?php

class Forum{
    //Attributs d'un forum
    private ?int $id;
    private ?string $nom;
    private ?string $description;
    private ?string $theme;

    //Constructeur de la classe Forum
    public function __construct(?int $id=null, ?string $nom=null, ?int $description=null, ?string $theme=null){
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->theme = $theme;
    }

    //Getters et setters de la classe Forum
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

    public function getDescription(): ?string{
        return $this->description;
    }

    public function setDescription(?string $description): void{
        $this->description = $description;
    }

    public function getTheme(): ?string{
        return $this->theme;
    }

    public function setTheme(?string $theme): void{
        $this->theme = $theme;
    }
}
?>
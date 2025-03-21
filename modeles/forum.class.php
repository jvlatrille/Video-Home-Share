<?php
    class Forum{
    //Attributs d'un forum
    private ?int $idForum;
    private ?string $nom;
    private ?string $description;
    private ?string $theme;
    private ?int $idUtilisateur;
    
    //Constructeur de la classe Forum
    public function __construct(?int $idForum=null, ?string $nom=null, ?string $description=null, ?string $theme=null, ?int $idUtilisateur=null){
        $this->idForum = $idForum;
        $this->nom = $nom;
        $this->description = $description;
        $this->theme = $theme;
        $this->idUtilisateur = $idUtilisateur;
    }

    //Getters et setters de la classe Forum

    public function getIdForum(): ?int{
        return $this->idForum;
    }

    public function setIdForum(?int $idForum): void{
        $this->idForum = $idForum;
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

    public function getIdUtilisateur(): ?int{
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?int $idUtilisateur): void{
        $this->idUtilisateur = $idUtilisateur;
    }

    }
?>
<?php


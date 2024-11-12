<?php

class Quizz {
    private ?int $idQuizz;
    private ?string $nom;
    private ?string $theme;
    private ?int $nbQuestion;
    private ?int $difficulte;
    private ?int $meilleurJ;

    // Constructeur
    public function __construct(?int $idQuizz, ?string $nom, ?string $theme, ?int $nbQuestion, ?int $difficulte, ?int $meilleurJ) {
        $this->idQuizz = $idQuizz;
        $this->nom = $nom;
        $this->theme = $theme;
        $this->nbQuestion = $nbQuestion;
        $this->difficulte = $difficulte;
        $this->meilleurJ = $meilleurJ;
    }

    // Getters et Setters
    public function getIdQuizz(): ?int {
        return $this->idQuizz;
    }

    public function setIdQuizz(?int $idQuizz): void {
        $this->idQuizz = $idQuizz;
    }

    public function getNom(): ?string {
        return $this->nom;
    }

    public function setNom(?string $nom): void {
        $this->nom = $nom;
    }

    public function getTheme(): ?string {
        return $this->theme;
    }

    public function setTheme(?string $theme): void {
        $this->theme = $theme;
    }

    public function getNbQuestion(): ?int {
        return $this->nbQuestion;
    }

    public function setNbQuestion(?int $nbQuestion): void {
        $this->nbQuestion = $nbQuestion;
    }

    public function getDifficulte(): ?int {
        return $this->difficulte;
    }

    public function setDifficulte(?int $difficulte): void {
        $this->difficulte = $difficulte;
    }

    public function getMeilleurJ(): ?int {
        return $this->meilleurJ;
    }

    public function setMeilleurJ(?int $meilleurJ): void {
        $this->meilleurJ = $meilleurJ;
    }
}
?>

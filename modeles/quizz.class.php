<?php

class Quizz {
    private ?int $idQuizz;
    private ?string $nom;
    private ?string $theme;
    private ?int $nbQuestion;
    private ?string $difficulte;

    // Constructeur
    public function __construct(?int $idQuizz, ?string $nom, ?string $theme, ?int $nbQuestion, ?string $difficulte) {
        $this->idQuizz = $idQuizz;
        $this->nom = $nom;
        $this->theme = $theme;
        $this->nbQuestion = $nbQuestion;
        $this->difficulte = $difficulte;
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

    public function getDifficulte(): ?string {
        return $this->difficulte;
    }

    public function setDifficulte(?string $difficulte): void {
        $this->difficulte = $difficulte;
    }
}
?>

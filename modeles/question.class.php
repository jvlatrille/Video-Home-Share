<?php
class question{
    private ?int $idQuestion;
    private ?string $contenu;
    private ?int $numero;
    private ?int $nvDifficulte;
    private ?int $bonneReponse;


	public function __construct(?int $idQuestion, ?string $contenu, ?int $numero, ?int $nvDifficulte, ?int $bonneReponse) {

		$this->idQuestion = $idQuestion;
		$this->contenu = $contenu;
		$this->numero = $numero;
		$this->nvDifficulte = $nvDifficulte;
		$this->bonneReponse = $bonneReponse;
	}

	public function getIdQuestion() : ?int {
		return $this->idQuestion;
	}

	public function setIdQuestion(?int $value) {
		$this->idQuestion = $value;
	}

	public function getContenu() : ?string {
		return $this->contenu;
	}

	public function setContenu(?string $value) {
		$this->contenu = $value;
	}

	public function getNumero() : ?int {
		return $this->numero;
	}

	public function setNumero(?int $value) {
		$this->numero = $value;
	}

	public function getNvDifficulte() : ?int {
		return $this->nvDifficulte;
	}

	public function setNvDifficulte(?int $value) {
		$this->nvDifficulte = $value;
	}

	public function getBonneReponse() : ?int {
		return $this->bonneReponse;
	}

	public function setBonneReponse(?int $value) {
		$this->bonneReponse = $value;
	}
}
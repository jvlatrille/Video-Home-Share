<?php
class question{
    private ?int $idQuestion;
    private ?string $contenu;
    private ?int $numero;
    private ?string $nvDifficulte;
    private ?string $bonneReponse;
	private ?string $cheminImage;
	private ?string $mauvaiseReponse1;
	private ?string $mauvaiseReponse2;
	private ?string $mauvaiseReponse3;
	private ?int $idQuizz;

	public function __construct(?int $idQuestion, ?string $contenu, ?int $numero, ?string $nvDifficulte, ?string $bonneReponse, ?string $cheminImage, ?string $mauvaiseReponse1,?string $mauvaiseReponse2, ?string $mauvaiseReponse3, ?int $idQuizz) {

		$this->idQuestion = $idQuestion;
		$this->contenu = $contenu;
		$this->numero = $numero;
		$this->nvDifficulte = $nvDifficulte;
		$this->bonneReponse = $bonneReponse;
		$this->cheminImage = $cheminImage;
		$this->mauvaiseReponse1 = $mauvaiseReponse1;
		$this->mauvaiseReponse2 = $mauvaiseReponse2;
		$this->mauvaiseReponse3 = $mauvaiseReponse3;
		$this->idQuizz = $idQuizz;
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

	public function getNvDifficulte() : ?string {
		return $this->nvDifficulte;
	}

	public function setNvDifficulte(?string $value) {
		$this->nvDifficulte = $value;
	}

	public function getBonneReponse() : ?string {
		return $this->bonneReponse;
	}

	public function setBonneReponse(?string $value) {
		$this->bonneReponse = $value;
	}
	public function getcheminImage() : ?string {
		return $this->cheminImage;
	}

	public function setcheminImage(?string $value) {
		$this->cheminImage = $value;
	}
	public function getMauvaiseReponse1() : ?string {
		return $this->mauvaiseReponse1;
	}

	public function setMauvaiseReponse1(?string $value) {
		$this->mauvaiseReponse1 = $value;
	}
	public function getMauvaiseReponse2() : ?string {
		return $this->mauvaiseReponse2;
	}

	public function setMauvaiseReponse2(?string $value) {
		$this->mauvaiseReponse2 = $value;
	}
	public function getMauvaiseReponse3() : ?string {
		return $this->mauvaiseReponse3;
	}

	public function setMauvaiseReponse3(?string $value) {
		$this->mauvaiseReponse3 = $value;
	}

	public function getIdQuizz() : ?int {
		return $this->idQuizz;
	}

	public function setIdQuizz(?int $id) {
		$this->idQuizz = $id;
	}
}
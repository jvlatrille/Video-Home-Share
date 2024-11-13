<?php

class ControllerJeux extends Controller{
	public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
	{
		parent::__construct($twig, $loader);
	}

	public function afficher()
    {
		// $template = $this->getTwig()->load("jeux.html.twig");
    	// echo $template->render();
	}

	public function listeTableau()
    {
		echo "listerTableau jeux";

		// Récupérer la liste des jeux
		$managerJeux = new JeuxDao($this->getPdo());
		$tableau = $managerJeux->findAll();

		// Choisir la page à charger
		$template = $this->getTwig()->load("jeux.html.twig");

		// Envoyer les données récupérer dans la page
		echo $template->render(array(
			'jeux' => $tableau
		));
	}

	public function lister(){
		echo "lister jeux";

		// Récupérer la liste des jeux
		$id = $_GET["id"];
		$managerJeux = new JeuxDao($this->getPdo());
		$jeu = $managerJeux->find($id);

		// Choisir la page à charger
		$template = $this->getTwig()->load("jeu.html.twig");

		// Envoyer les données récupérer dans la page
		echo $template->render(array(
			'jeu' => $jeu
		));
	}
}
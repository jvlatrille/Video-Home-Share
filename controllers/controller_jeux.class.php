<?php

/**
 * Une classe pour représenter les jeux de l'application
 */
class ControllerJeux extends Controller{
	/**
	 * Fonction qui permet de construire l'objet grâce au constructeur de son parent
	 *
	 * @param \Twig\Environment $twig 
	 * @param \Twig\Loader\FilesystemLoader $loader 
	 */
	public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
	{
		parent::__construct($twig, $loader);
	}

	public function afficher()
    {
		$template = $this->getTwig()->load("jeux.html.twig");
    	echo $template->render();
	}

	/**
	 * Fonction qui permet de d'afficher l'ensemble des jeux disponible
	 *
	 * @return void 
	 */
	public function listeTableau()
    {
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

	/**
	 * Fonction qui permet d'afficher un seul jeu en fonction de l'id choisi
	 *
	 * @return void
	 */
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
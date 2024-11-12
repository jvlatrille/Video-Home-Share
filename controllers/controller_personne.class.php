<?php

class ControllerPersonne extends Controller
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    // Afficher toutes les personnes
    public function listerPersonnes()
    {
        // Récupère toutes les personnes
        $managerPersonne = new PersonneDAO($this->getPdo());
        $personnesListe = $managerPersonne->findAll();

        // Génère la vue
        $template = $this->getTwig()->load('personne.html.twig');
        echo $template->render(['personnesListe' => $personnesListe]);
    }

    // Afficher une personne spécifique
    public function afficherPersonne()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        // Récupère la personne
        $managerPersonne = new PersonneDAO($this->getPdo());
        $personne = $managerPersonne->find($id);

        // Génère la vue
        $template = $this->getTwig()->load('personne_detail.html.twig');
        echo $template->render(['personne' => $personne]);
    }
}

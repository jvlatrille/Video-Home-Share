<?php

class ControllerUtilisateur extends Controller
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    // Afficher tous les utilisateurs
    public function AllUtilisateurs()
    {
        // Récupère toutes les utilisateur
        $managerUtilisateur = new UtilisateurDAO($this->getPdo());
        $utilisateurListe = $managerUtilisateur->findAll();

        // Génère la vue
        $template = $this->getTwig()->load('utilisateur.html.twig');
        echo $template->render(['utilisateurListe' => $utilisateurListe]);
    }

    // Afficher un seul utilisateurs
    public function afficherUtilisateur()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        // Récupère l'utilisateur'
        $managerUtilisateur = new UtilisateurDAO($this->getPdo());
        $unUtilisateur = $managerUtilisateur->find($id);

        // Génère la vue
        $template = $this->getTwig()->load('utilisateur_detail.html.twig');
        echo $template->render(['utilisateur' => $unUtilisateur]);
    }
}
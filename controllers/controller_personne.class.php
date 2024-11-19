<?php

/**
 * @file controller_personne.class.php
 * @author VINET LATRILLE Jules
 * @brief Contrôleur pour gérer les actions liées aux Personnes
 * @details Ce contrôleur permet de lister les personnes et d'afficher les détails d'une personne spécifique.
 * @version 1.0
 * @date 17/11/2024
 */

class ControllerPersonne extends Controller
{
    /**
     * @brief Constructeur de la classe ControllerPersonne
     * @param \Twig\Environment $twig Environnement Twig
     * @param \Twig\Loader\FilesystemLoader $loader Loader de fichiers Twig
     */
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    /**
     * @brief Liste toutes les personnes
     * @details Cette méthode récupère toutes les personnes via le DAO et génère la vue correspondante.
     * @return void
     */
    public function listerPersonnes()
    {
        // Récupère les paramètres pour la pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $itemsPerPage = 50; // Nombre de personnes par page
        $offset = ($page - 1) * $itemsPerPage;

        // Récupère les personnes avec une limite
        $managerPersonne = new PersonneDAO($this->getPdo());
        $personnesListe = $managerPersonne->findAll($itemsPerPage, $offset);

        // Génère la vue
        $template = $this->getTwig()->load('personne.html.twig');
        echo $template->render([
            'personnesListe' => $personnesListe,
            'page' => $page,
        ]);
    }

    /**
     * @brief Affiche les détails d'une personne
     * @details Cette méthode récupère les informations d'une personne spécifique via son ID et génère la vue correspondante.
     * @return void
     */
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

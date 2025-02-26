<?php

/**
 * @file controller_index.class.php
 * @brief Contrôleur pour la page d'accueil
 * @details Ce contrôleur gère l'affichage de la page d'accueil et la recherche de films.
 * @version 2.0
 * @date 2025-01-09
 * @author CHIPY Thibault
 */
class ControllerIndex extends Controller
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    /**
     * @brief Affiche la page d'accueil avec les 10 films les mieux notés 
     *
     * @return void
     */
    public function render()
    {
        $managerOa = new OADao();
        $oaListe = $managerOa->findMeilleurNote();
        $oaListe = array_merge($oaListe, $managerOa->findMeilleurNoteSerie());
        shuffle($oaListe);
        $oaListe = array_slice($oaListe, 0, 20);
        $managerMessage = new MessageDAO($this->getPdo());
        $topMessages = $managerMessage->getTopLikedMessages();
        $randomListe = $managerOa->findRandomOeuvres();
        $template = $this->getTwig()->load('index.html.twig');
        echo $template->render([
            'oaListe' => $oaListe,
            'topMessages' => $topMessages,
            'oaRandomListe'=> $randomListe
        ]);
    }


    /**
     * @brief Recherche un film par son nom
     *
     * @return void
     */
    public function rechercher()
    {
        $requete = htmlspecialchars($_GET['requete']) ?? null;
        $managerOa = new OADao();
        $oas = $managerOa->rechercheFilmParNom($requete);

        $template = $this->getTwig()->load('recherche.html.twig');
        echo $template->render(['oas' => $oas, 'requete' => $requete]);
    }
}

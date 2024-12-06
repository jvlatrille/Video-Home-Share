<?php

/**
 * @file controller_oa.class.php
 * @author Thibault CHIPY 
 * @brief Controleur des oeuvres audiovisuelles OA 
 * 
 * @version 1.0
 * @date 11/11/2024
 */

class ControllerOA extends Controller
{
    /**
     * @brief Constructeur de la classe ControllerOA
     *
     * @param \Twig\Environment $twig Envrironnement twig
     * @param \Twig\Loader\FilesystemLoader $loader loader de fichiers twig
     */
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    /////////////////////////////////////////  
    // La fonction listerFilms sera celle qui sera de base appelée par le controller. Elle permettra d'afficher la liste des 10 films les mieux notés.
    /////////////////////////////////////////

    /**
     * @brief Methode pour lister les films avec les meilleures notes sur la page d'acceuil
     * 
     *@remark cete methode est appelée par defaut par le controller quand on arrive sur la page d'acceuil du site
     * @return void
     */
    public function listerFilms()
    {
        // Recupere tous les films
        $managerOA = new OADao($this->getPdo());
        $oaListe = $managerOA->findMeilleurNote();

        // Generer la vue
        $template = $this->getTwig()->load('index.html.twig');
        // var_dump($oaListe);
        echo $template->render(['oaListe' => $oaListe]);
    }

    //Fonction pour afficher un film, ajout de la watchlist pour afficher les watchlists de l'utilisateur s'il veut ajouter le film à une watchlist
    /**
     * @brief Methode pour afficher un film
     *@details Cette methode recupere toutes les WatchLists de l'utilisateur connecté et l'oeuvre audiovisuelle à afficher
     * @return void 
     */
    public function afficherFilm()
    {
        // Récupérer l'ID du film depuis l'URL
        $idOa = isset($_GET['idOa']) ? $_GET['idOa'] : null;

        // Instancier les DAOs nécessaires
        $managerOa = new OADao($this->getPdo());
        $managerWatchList = new WatchListDao($this->getPdo()); // Si tu as besoin d'utiliser les watchlists

        // Récupérer les informations du film
        $oa = $managerOa->find($idOa);

        // Récupérer les participants associés au film
        $participants = $managerOa->getParticipantsByFilmId($idOa);

        // Récupérer les watchlists si nécessaire
        $watchListListe = $managerWatchList->findAll(1); // Pour les tests, idUtilisateur = 1

        // Générer la vue
        $template = $this->getTwig()->load('film.html.twig');
        echo $template->render([
            'oa' => $oa,
            'participants' => $participants,
            'watchListListe' => $watchListListe
        ]);
    }
}

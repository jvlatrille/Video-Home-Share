<?php

class ControllerOA extends Controller
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

/////////////////////////////////////////  
// La fonction afficherFilms sera celle qui sera de base appelée par le controller. Elle permettra d'afficher la liste des films.
/////////////////////////////////////////

    public function listerFilms()
    {
        // Recupere tous les films
        $managerOA = new OADao($this->getPdo());
        $oaListe = $managerOA->findAll();
 
        
        // Generer la vue
        $template = $this->getTwig()->load('index.html.twig');
        
        echo $template->render(['oaListe' => $oaListe]);
    }

    //Fonction pour afficher un film, ajout de la watchlist pour afficher les watchlists de l'utilisateur s'il veut ajouter le film à une watchlist
    public function afficherFilm()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        

      // Recupere toutes les watchlists
      $managerWatchList = new WatchListDao($this->getPdo());
      $watchListListe = $managerWatchList->findAll(1); // normalement $_SESSION['idUtilisateur']
                                                       // mais pour les tests on met 1
      // Recupere l'oa
      $idOa= isset($_GET['id']) ? $_GET['id'] : null;
      $managerOa = new OADao($this->getPdo());
      $oa = $managerOa->find($idOa);
      // Generer la vue
      $template = $this->getTwig()->load('film.html.twig');
      
      echo $template->render(['watchListListe' => $watchListListe, 'oa' => $oa]);

    }
    }

    
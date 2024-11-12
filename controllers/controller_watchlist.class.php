<?php
class ControllerWatchList extends Controller{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    //Fonction pour lister les watchlists d'un utilisateur
    public function listerWatchList()
    {
        // Recupere toutes les watchlists
        $managerWatchList = new WatchListDao($this->getPdo());
        $watchListListe = $managerWatchList->findAll(1); // normalement $_SESSION['idUtilisateur']
                                                         // mais pour les tests on met 1
        
        // Generer la vue
        $template = $this->getTwig()->load('watchlists.html.twig');
        
        echo $template->render(['watchListListe' => $watchListListe]);
    }        

    //Fonction pour afficher une watchlist
    public function afficherWatchList()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        
        //Recupere la watchlist
        $managerWatchList=New WatchListDao($this->getPdo());
        $watchList=$managerWatchList->find($id);
        //Generer la vue
        $template = $this->getTwig()->load('watchlist.html.twig');
        
        echo $template->render(['watchList'=>$watchList]);

    }

}
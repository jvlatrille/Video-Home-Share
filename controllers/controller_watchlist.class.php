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

    //Fonction pour lister toutes les watchlists visibles
    public function listerWatchListVisible()
    {
        // Recupere toutes les watchlists visibles
        $managerWatchList = new WatchListDao($this->getPdo());
        $watchListListe = $managerWatchList->findAllVisible(1); //Id toujours 1 pour les tests mais normalement $_SESSION['idUtilisateur']
        
        // Generer la vue
        $template = $this->getTwig()->load('watchlistsCommu.html.twig');
        
        echo $template->render(['watchListListe' => $watchListListe]);
    }


    //Fonction pour ajouter une watchlist
    public function ajouterWatchList()
    {
        //Recupere les données du formulaire
        $titre = isset($_POST['titre']) ? $_POST['titre'] : null;
        $genre = isset($_POST['genre']) ? $_POST['genre'] : null;
        $description = isset($_POST['description']) ? $_POST['description'] : null;
        $visible = isset($_POST['visible']) ? $_POST['visible'] : null;
        var_dump($titre, $genre, $description, $visible);
        //Ajoute la watchlist
        $managerWatchList = new WatchListDao($this->getPdo());
        $watchList = new WatchList();
        $watchList->setTitre($titre);
        $watchList->setGenre($genre);
        $watchList->setDescription($description);
        $watchList->setVisible($visible);
        //$watchList->setIdUtilisateur(1); //Id toujours 1 pour les tests mais normalement $_SESSION['idUtilisateur']
        $managerWatchList->creerWatchlist($watchList);
        
        //Redirige vers la liste des watchlists
        header('Location: index.php?controleur=watchlist&methode=listerWatchList&id=1'); //Id toujours 1 pour les tests mais normalement $_SESSION['idUtilisateur']
    }    

} 
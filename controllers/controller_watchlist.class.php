<?php

/**
 * @file controller_watchlist.class.php
 * @author Thibault CHIPY 
 * @brief Controleur des watchlists 
 * 
 * @version 2.0
 * @date 14/11/2024
 */
class ControllerWatchList extends Controller
{

    /**
     * @brief Constructeur de la classe ControllerWatchList
     *
     * @param \Twig\Environment $twig Envrironnement twig
     * @param \Twig\Loader\FilesystemLoader $loader loader de fichiers twig
     */
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    /**
     * @brief Methode pour lister les watchlists de l'utilisateur
     *
     * @return void
     */
    //Fonction pour lister les watchlists d'un utilisateur
    public function listerWatchList()
    {
        // Vérifie si un utilisateur est connecté
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);

            // Recupere toutes les watchlists
            $managerWatchList = new WatchListDao($this->getPdo());
            $watchListListe = $managerWatchList->findAll($utilisateurConnecte->getIdUtilisateur());

            // Generer la vue
            $template = $this->getTwig()->load('watchlists.html.twig');

            echo $template->render([
                'watchListListe' => $watchListListe,
            ]);
            } 
        else {
            // Redirige vers la page de connexion
            header('Location: index.php?controleur=utilisateur&methode=connexion');
        }
    }

    /**
     * @brief Methode pour afficher une Watchlist et ses oeuvres
     *
     * @return void
     */
    //Fonction pour afficher une watchlist
    public function afficherWatchList()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        //Recupere la watchlist
        $managerWatchList = new WatchListDao($this->getPdo());
        $watchList = $managerWatchList->find($id);

        //Recupere les oeuvres de la watchlist

        $oas = $managerWatchList->afficherOaWatchlist($id);
        
        //Generer la vue
        $template = $this->getTwig()->load('watchlist.html.twig');

        echo $template->render(['watchList' => $watchList, 'oas' => $oas]);
    }

    /**
     * @brief Methode pour lister toutes les watchlists visibles qui ne sont pas à l'utilisateur connecté
     *
     * @return void
     */
    //Fonction pour lister toutes les watchlists visibles
    public function listerWatchListVisible()
    {
        // Vérifie si un utilisateur est connecté
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);


            $managerWatchList = new WatchListDao($this->getPdo());

            // Récupère toutes les watchlists visibles
            $watchListListe = $managerWatchList->findAllVisibleWithFilms($utilisateurConnecte->getIdUtilisateur());
            // Génère la vue
            $template = $this->getTwig()->load('watchlistsCommu.html.twig');
            echo $template->render(['watchListListe' => $watchListListe]);
        }
    }
    //Fonction pour ajouter une Watchlist
    /**
     * @brief Methode pour ajouter une watchlist à l'utilisateur
     *@bug La fonction ne fonctionne pas, elle n'insert pas les données dans la base de données
     * @return void
     */
    public function ajouterWatchList()
    {

        // Vérifie si un utilisateur est connecté
    
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);

            //Recupere les données de la watchlist du formulaire
            $idWatchList = isset($_POST['idWatchList']) ? $_POST['idWatchList'] : null;
            $titre = isset($_POST['titre']) ? $_POST['titre'] : (isset($_GET['titre']) ? $_GET['titre'] : null);
            $genre = isset($_POST['genre']) ? $_POST['genre'] : (isset($_GET['genre']) ? $_GET['genre'] : null);
            $description = isset($_POST['description']) ? $_POST['description'] : (isset($_GET['description']) ? $_GET['description'] : null);
            $visible = isset($_POST['visible']) ? $_POST['visible'] : (isset($_GET['visible']) ? $_GET['visible'] : null);

            $idUtilisateur = $utilisateurConnecte->getIdUtilisateur();

            //Ajoute la watchlist
            $managerWatchList = new WatchListDao($this->getPdo());
            $watchList = new WatchList();
            $watchList->setIdWatchList($idWatchList);
            $watchList->setTitre($titre);
            $watchList->setGenre($genre);
            $watchList->setDescription($description);
            $watchList->setVisible($visible);
            $watchList->setIdUtilisateur($idUtilisateur);
            $managerWatchList->creerWatchlist($watchList);

            //Recupere les oeuvres de la watchlist, pour chaque idOeuvre, on ajoute l'oeuvre à la watchlist
            $idOas = isset($_POST['listeOeuvres']) ? $_POST['listeOeuvres'] : null;

            if ($idOas !== null) {
                foreach ($idOas as $idOa) {
                    $managerWatchList->ajouterOAWatchlist($watchList->getIdWatchlist(), $idOa);
                }
            } else {
                echo "Aucune œuvre sélectionnée.";
            }


            //Redirige vers la liste des watchlists
            header('Location: index.php?controleur=watchlist&methode=listerWatchList&id=' . $idUtilisateur . '');
        }
    }

    //Fonction pour modifier une Watchlist
    /**
     * @brief Methode pour modifier une Watchlist
     *@remark peut etre pas utile car nous ne proposons pas de fonctionnalité de modification de watchlist
     * @return void
     */
    public function modifierWatchList()
    {
        //Recupere les données du formulaire
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $titre = isset($_POST['titre']) ? $_POST['titre'] : null;
        $genre = isset($_POST['genre']) ? $_POST['genre'] : null;
        $description = isset($_POST['description']) ? $_POST['description'] : null;
        $visible = isset($_POST['visible']) ? $_POST['visible'] : null;

        //Modifie la watchlist
        $managerWatchList = new WatchListDao($this->getPdo());
        $watchList = new WatchList();
        $watchList->setIdWatchList($id);
        $watchList->setTitre($titre);
        $watchList->setGenre($genre);
        $watchList->setDescription($description);
        $watchList->setVisible($visible);
        $managerWatchList->modifierWatchlist($watchList);

        //Redirige vers la liste des watchlists
        header('Location: index.php?controleur=watchlist&methode=listerWatchList&id=1'); //Id toujours 1 pour les tests mais normalement $_SESSION['idUtilisateur']
    }


    //Fonction pour supprimer une watchlist
    /**
     * @brief Methode pour supprimer une Watchlist
     * @return void
     */
    public function supprimerWatchList()
    {
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);

            //Recupere l'id de la watchlist
            $idWatchList = isset($_GET['id']) ? $_GET['id'] : null;
            $idUtilisateur = $utilisateurConnecte->getIdUtilisateur(); //Id toujours 1 pour les tests mais normalement $_SESSION['idUtilisateur']

            //Supprime la watchlist
            $managerWatchList = new WatchListDao($this->getPdo());
            $managerWatchList->supprimerUneWatchlist($idWatchList, $idUtilisateur);
        }

        //Redirige vers la liste des watchlists
        header('Location: index.php?controleur=watchlist&methode=listerWatchList&id='.$idUtilisateur.'');
    }


    //Fonction pour ajouter une oeuvre à une watchlist
    /**
     * @brief Methode pour ajouter une oeuvre OA à une Watchlist
     *
     * @return void
     */
    public function ajouterOaWatchList()
    {
        //Recupere les données du formulaire
        $idWatchList = isset($_POST['idWatchList']) ? $_POST['idWatchList'] : null;
        $idOeuvre = isset($_POST['idOeuvre']) ? $_POST['idOeuvre'] : null;

        //Ajoute l'oeuvre à la watchlist
        $managerWatchList = new WatchListDao($this->getPdo());
        $managerWatchList->ajouterOAWatchlist($idWatchList, $idOeuvre);

        //Redirige vers la liste des watchlists
        header('Location: index.php?controleur=watchlist&methode=afficherWatchList&id=' . $idWatchList);
    }

    //Fonction pour supprimer une oeuvre d'une watchlist
    /**
     * @brief Methode pour supprimer une oeuvre OA d'une Watchlist
     *
     * @return void
     */
    public function supprimerOaWatchList()
    {
        //Recupere les données du formulaire
        $idWatchList = isset($_POST['idWathlist']) ? $_POST['ididWathlist'] : (isset($_GET['idWathlist']) ? $_GET['idWathlist'] : null);
        $idOeuvre = isset($_POST['idOeuvre']) ? $_POST['idOeuvre'] : (isset($_GET['idOeuvre']) ? $_GET['idOeuvre'] : null);

        //Supprime l'oeuvre de la watchlist
        $managerWatchList = new WatchListDao($this->getPdo());
        $managerWatchList->supprimerOA($idWatchList, $idOeuvre);

        //Redirige vers la liste des watchlists
        header('Location: index.php?controleur=watchlist&methode=afficherWatchList&id=' . $idWatchList);
    }
}

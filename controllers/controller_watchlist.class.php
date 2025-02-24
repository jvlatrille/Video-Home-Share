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

            //Recuperer des OA pour les suggestions
            // $managerOa = new OaDao($this->getPdo());
            // $oas = $managerOa->findRandomOeuvres();

            // $oas=array_merge($oas, $managerOa->findRandomSeries());
            // $oas=array_slice($oas, 0, 15);
            // shuffle($oas);
            // Generer la vue
            $template = $this->getTwig()->load('watchlists.html.twig');

            echo $template->render([
                'watchListListe' => $watchListListe,
                // 'oas' => $oas
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
        $id = $_GET['idWatchlist'] ?? null;
      //Recupere la watchlist
        $managerWatchList = new WatchListDao($this->getPdo());
        
        
        $watchList = $managerWatchList->findWithFilms($id);

        //Generer la vue
        $template = $this->getTwig()->load('watchlist.html.twig');

        echo $template->render(['watchList' => $watchList]);
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
            $watchListListe = $managerWatchList->findAllVisibleWithFilm($utilisateurConnecte->getIdUtilisateur());  
            // Génère la vue
            $template = $this->getTwig()->load('watchlistsCommu.html.twig');
            echo $template->render(['watchListListe' => $watchListListe]);
        }
    }
    //Fonction pour ajouter une Watchlist
    /**
     * @brief Methode pour ajouter une watchlist à l'utilisateur
     * @return void
     */
    public function ajouterWatchList(){

    // Vérifie si un utilisateur est connecté
    if (isset($_SESSION['utilisateur'])){
        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        // Récupération des données du formulaire
        $donnees = [
            'idWatchList' => $_POST['idWatchList'] ?? null,
            'titre' => $_POST['titre'] ?? null,
            'genre' => explode(':',$_POST['selectedGenre'])[1] ?? $_POST["selectedGenre"] ?? null,
            'description' => $_POST['description'] ?? null,
            'visible' => $_POST['visible'] ?? '0',
            'OAs' => is_string($_POST['OAs']) ? json_decode($_POST['OAs'], true) : $_POST['OAs'],
        ];
   
        // Définition des règles de validation
        $regles = [
            'titre' => [
                'obligatoire' => true,
                'type' => 'string',
                'longueur_min' => 1,
                'longueur_max' => 255,
            ],
            'genre' => [
                'obligatoire' => true,
                'type' => 'string',
                'longueur_min' => 1,
                'longueur_max' => 255,
            ],
            'description' => [
                'obligatoire' => true,
                'type' => 'string',
                'longueur_min' => 1,
                'longueur_max' => 255,
            ],
            'visible' => [
                'obligatoire' => true,
                'type' => 'string',
            ],
        ];

        // Validation des données
        $validation = new Validator($regles);

        if (!$validation->valider($donnees)) {
            $erreurs = $validation->getMessagesErreurs();
            $template = $this->getTwig()->load('watchlists.html.twig');
            echo $template->render(['erreurs' => $erreurs]);
            return;
        }

        $idWatchList = $donnees['idWatchList'] ?? null;
        $titre = $donnees['titre'];
        $genre = $donnees['genre'] ?? null;
        $description = $donnees['description'];
        $visible = $donnees['visible'];
        $idUtilisateur = $utilisateurConnecte->getIdUtilisateur();

        // Traitement des œuvres (idTMDB et type)
        $listeOeuvres = $donnees['OAs'] ?? [];
        
        $oeuvresFormatees = [];

        foreach ($listeOeuvres as $oeuvre) {
            if (is_string($oeuvre)) {
                $idTMDB=explode(':', $oeuvre);
                $oeuvresFormatees[] = [
                    'idTMDB' => $idTMDB[0],
                    'type' => $idTMDB[1],
                ];
            }
        }
    

        // Création de la watchlist
        $managerWatchList = new WatchListDao($this->getPdo());
        $watchList = new WatchList();
        $watchList->setIdWatchList($idWatchList);
        $watchList->setTitre($titre);
        $watchList->setGenre($genre);
        $watchList->setDescription($description);
        $watchList->setVisible($visible);
        $watchList->setIdUtilisateur($idUtilisateur);
        $managerWatchList->creerWatchlist($watchList);

        var_dump($watchList);

        $idNouvelleWatchlist = $watchList->getIdWatchList();
        var_dump($idNouvelleWatchlist);

        // Association des œuvres à la watchlist
        foreach ($oeuvresFormatees as $oeuvre) {
            var_dump("entree");
            $managerWatchList->addOaToWatchlist($idNouvelleWatchlist, $oeuvre['idTMDB'], $oeuvre['type']);
        }

        var_dump("fait addOaToWatchlist");
        var_dump($watchList);

        // Redirection vers la liste des watchlists
        header('Location: index.php?controleur=watchlist&methode=listerWatchList&id=' . $idUtilisateur);
        exit();
    }
    else {
        // Redirection vers la page de connexion
        header('Location: index.php?controleur=utilisateur&methode=connexion');
    }
}


    //Fonction pour modifier une Watchlist
    /**
     * @brief Methode pour modifier une Watchlist
     * 
     * @return void
     */
    public function modifierWatchList()
    {
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        //Recupere les données du formulaire 
        $donnees = [
            'id' => $_GET['id'] ?? null,
            'titre' => $_POST['titre'] ?? null,
            'genre' => $_POST['genre'] ?? null,
            'description' => $_POST['description'] ?? null,
            'visible' => $_POST['visible'] ?? null,
        ];
        $regles = [
            'titre' => [
                'obligatoire' => true,
                 'type' => 'string',
                'longueur_min' => 1,
                'longueur_max' => 255,
            ],
            'genre' => [
                'obligatoire' => true,
                'type' => 'string',
                'longueur_min' => 1,
                'longueur_max' => 255,
            ],
            'description' => [
                'obligatoire' => true,
                'type' => 'string',
                'longueur_min' => 1,
                'longueur_max' => 255,
            ],
        ];
        $validation = new Validator($regles);
        if(!$validation->valider($donnees)){
            $erreurs = $validation->getMessagesErreurs();
            $template = $this->getTwig()->load('watchlist.html.twig');
            echo $template->render(['erreurs' => $erreurs]);
            return;
        }
        $id = $donnees['id'];
        $titre = $donnees['titre'];
        $genre = $donnees['genre'];
        $description = $donnees['description'];
        $visible = $donnees['visible'];
        

        //Modifie la watchlist
        $managerWatchList = new WatchListDao($this->getPdo());
        $watchList = new WatchList();
        $watchList->setIdWatchList($id);
        $watchList->setTitre($titre);
        $watchList->setGenre($genre);
        $watchList->setDescription($description);
        $watchList->setVisible($visible);
        $managerWatchList->modifierWatchlistComplete($watchList);

        //Redirige vers la watchlist
        header('Location: index.php?controleur=watchlist&methode=afficherWatchlist&idWatchlist=' . $watchList->getIdWatchlist() . ''); 
        }
        else
        {
            // Redirige vers la page de connexion
            header('Location: index.php?controleur=utilisateur&methode=connexion');
        }
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
        $donnees = [
            'idWatchList' => $_POST['idWatchList'] ?? null,
            'idOeuvre' => $_POST['idOeuvre'] ?? null,
            'type' => $_POST['type'] ?? null,
        ];

        $regles = [
            'idWatchList' => [
                'obligatoire' => true,
                'type' => 'int',
                'longueur_min' => 1,
                'longueur_max' => 255,
            ],
            'idOeuvre' => [
                'obligatoire' => true,
                'type' => 'int',
                'longueur_min' => 1,
                'longueur_max' => 255,
            ],
            'type' => [
                'obligatoire' => true,
                'type' => 'string',
                'longueur_min' => 1,
                'longueur_max' => 255,
            ],
        ];

        $validation = new Validator($regles);

        if(!$validation->valider($donnees)){
            $erreurs = $validation->getMessagesErreurs();
            $template = $this->getTwig()->load('watchlists.html.twig');
            echo $template->render(['erreurs' => $erreurs]);
            return;
        }

        $idWatchList = $donnees['idWatchList'];
        $idOeuvre = $donnees['idOeuvre'];
        $type = $donnees['type'];

        //Vérifie si l'oeuvre est déjà dans la watchlist
        $managerWatchList = new WatchListDao($this->getPdo());
        $watchList = $managerWatchList->findWithFilms($idWatchList);
        $oas = $watchList->getListeOeuvres();
        foreach ($oas as $oa) {
            if ($oa->getIdOa() == $idOeuvre) {
                header('Location: index.php?controleur=watchlist&methode=afficherWatchList&idWatchlist=' . $idWatchList);
                return;
            }
        }


        //Ajoute l'oeuvre à la watchlist
        $managerWatchList->addOaToWatchlist($idWatchList, $idOeuvre,$type);
        //Recuperer tous les oeuvres de la watchlist
        $oas = $managerWatchList->findOeuvresWatchlist($idWatchList);

        //Mettre a jour le genre de la watchlist
        $genreDominant = $managerWatchList->calculGenreDominantWatchlist($oas);

        //modifier la watchlist
        $watchList = $managerWatchList->findWithFilms($idWatchList);
        $watchList->setGenre($genreDominant);
        $managerWatchList->modifierWatchlistComplete($watchList);

        //Redirige vers la liste des watchlists
        header('Location: index.php?controleur=watchlist&methode=listerWatchlist&id=' . $idWatchList);
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
        $donnees = [
            'idWatchlist' => isset($_POST['idWatchlist']) ? (int)$_POST['idWatchlist'] : (isset($_GET['idWatchlist']) ? (int)$_GET['idWatchlist'] : null),
            'idOeuvre' => isset($_POST['idOeuvre']) ? (int)$_POST['idOeuvre'] : (isset($_GET['idOeuvre']) ? (int)$_GET['idOeuvre'] : null),
        ];
        $regles = [
            'idWatchlist' => [
                'obligatoire' => true,
                'type' => 'int',

            ],
            'idOeuvre' => [
                'obligatoire' => true,
                'type' => 'int',

            ],
        ];
        $validation = new Validator($regles);
        if(!$validation->valider($donnees)){
            $erreurs = $validation->getMessagesErreurs();
            $template = $this->getTwig()->load('watchlist.html.twig');
            echo $template->render(['erreurs' => $erreurs]);
            return;
        }
        $idWatchList = $donnees['idWatchlist'];
        $idOeuvre = $donnees['idOeuvre'];

        //Supprime l'oeuvre de la watchlist
        $managerWatchList = new WatchListDao($this->getPdo());
        $managerWatchList->supprimerOA($idWatchList, $idOeuvre);

        //Calcul le genre
        $oas = $managerWatchList->findOeuvresWatchlist($idWatchList);
        $genreDominant = $managerWatchList->calculGenreDominantWatchlist($oas);

        //modifier la watchlist
        $watchList = $managerWatchList->findWithFilms($idWatchList);
        $watchList->setGenre($genreDominant);
        $managerWatchList->modifierWatchlistComplete($watchList);


        //Redirige vers la liste des watchlists
       header('Location: index.php?controleur=watchlist&methode=afficherWatchList&idWatchlist=' . $idWatchList);
    }
   }

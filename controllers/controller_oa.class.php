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
// La fonction afficherFilms sera celle qui sera de base appelée par le controller. Elle permettra d'afficher la liste des 10 films les mieux notés.
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
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        

      // Recupere toutes les watchlists
      $managerWatchList = new WatchListDao($this->getPdo()); //je sais pas si c'est légal
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

    
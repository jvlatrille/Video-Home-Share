<?php

class ControllerForum extends Controller
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    // Afficher tous les forums
    public function listerForum()
    {
        // Récupère tous les forums
        $managerForum = new ForumDAO($this->getPdo());
        $forumsListe = $managerForum->findAll();

        // Génère la vue
        $template = $this->getTwig()->load('forums.html.twig');
        echo $template->render(['forumListe' => $forumsListe]);
    }

    public function ajouterForum()
    {
        //Recupere les données du formulaire
        $nom = isset($_POST['nom']) ? $_POST['nom'] : (isset($_GET['nom']) ? $_GET['nom'] : null);
        $description = isset($_POST['description']) ? $_POST['description'] : (isset($_GET['description']) ? $_GET['description'] : null);
        $theme = isset($_POST['theme']) ? $_POST['theme'] : (isset($_GET['theme']) ? $_GET['theme'] : null);
        //Ajoute la watchlist
        $managerForum = new ForumDao($this->getPdo());
        $forum = new Forum();
        $forum->setNom($nom);
        $forum->setTheme($theme);
        $forum->setDescription($description);
        $managerForum->creerForum($forum);
        
        //Redirige vers la liste des forums
        header('Location: index.php?controleur=forum&methode=listerForum');
    }

}
?>
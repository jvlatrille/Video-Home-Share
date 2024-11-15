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

    //Fonction pour afficher un forum
    public function afficherForum()
    {
        $id = isset($_GET['idForum']) ? $_GET['idForum'] : null;
        
        //Recupere le forum
        $managerForum=New forumDAO($this->getPdo());
        $forumList=$managerForum->find($id);
        
        //Recupere les noms des forums
        $noms = $managerForum->afficherNomForum($idForum);
        
        //Generer la vue
        $template = $this->getTwig()->load('unForum.html.twig');
        
        echo $template->render(['forum'=>$forumList, 'noms'=>$noms]);

    }



}
?>
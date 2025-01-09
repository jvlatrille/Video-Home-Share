<?php

class ControllerIndex extends Controller{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    // Render base template
    public function render()
    {

        $managerOa = new OADao();
        $oaListe = $managerOa->findMeilleurNote();
        $template = $this->getTwig()->load('index.html.twig');
        echo $template->render(['oaListe' => $oaListe]);

    }

    public function rechercherFilm(){
        $requete=htmlentities($_POST['requete']) ?? null;

        $managerOa = new OADao();
        $oas = $managerOa->rechercheFilmParNom($requete);

        $template = $this->getTwig()->load('recherche.html.twig');
        echo $template->render(['oas' => $oas]);



    }
}
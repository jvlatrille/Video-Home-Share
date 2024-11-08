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

    public function afficherFilm()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        

        //Recupere le film
        $managerOA=New OADao($this->getPdo());
        $oa=$managerOA->find($id);
        //Generer la vue
        $template = $this->getTwig()->load('film.html.twig');
        
        echo $template->render(['oa'=>$oa]);

    }
    }

    
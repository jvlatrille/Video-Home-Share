<?php

class ControllerOA extends Controller
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    public function afficherFilms()
    {
        //Recupere tous les films
    //  $idOA=isset($_GET['idOA']) ? $_GET['idOA'] : null;
     $managerOA=New OADao($this->getPdo());
    $oa=$managerOA->findAll();

    //Generer la vue
    $template = $this->getTwig()->load('films.html.twig');
    
    echo $template->render(['oa'=>$oa]);
}

}
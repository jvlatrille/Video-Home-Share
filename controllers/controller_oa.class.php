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
    $managerOA=New OADao($this->getPdo());
    $oa=$managerOA->find(28);
    var_dump($oa);
        
    //Generer la vue
    $template = $this->getTwig()->load('index.html.twig');
    
    echo $template->render(['oa'=>$oa]);
}

}
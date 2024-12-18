<?php

class ControllerOA extends Controller
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    public function listerFilms()
    {
        $managerOA = new OADao();
        $oaListe = $managerOA->findMeilleurNote();

        $template = $this->getTwig()->load('index.html.twig');
        echo $template->render(['oaListe' => $oaListe]);
    }

    public function afficherFilm()
    {
        $idOa = $_GET['idOa'] ?? null;
        $managerOa = new OADao();
        $oa = $managerOa->find($idOa);

        // Récupérer les commentaires associés à l'idTMDB
        $commentaires = $managerOa->getCommentairesByTMDB($idOa);
var_dump($commentaires);
        $template = $this->getTwig()->load('film.html.twig');
        echo $template->render([
            'oa' => $oa,
            'commentaires' => $commentaires
        ]);
    }
}

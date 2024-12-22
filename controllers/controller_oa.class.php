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

        if (!is_numeric($idOa) || (int)$idOa <= 0) {
            die('ID du film invalide ou non spécifié.');
        }

        $idOa = (int)$idOa;

        $managerOa = new OADao($this->getPdo());
        $managerCommentaire = new CommentaireDAO($this->getPdo());

        $oa = $managerOa->find($idOa);
        if (!$oa) {
            die('Film non trouvé.');
        }

        $commentaires = $managerCommentaire->findByTMDB($oa->getIdOa());

        // Log pour vérifier
        error_log("Nombre de commentaires : " . count($commentaires));

        foreach ($commentaires as $commentaire) {
            error_log("Commentaire ID : " . $commentaire->getIdCom());
        }

        $template = $this->getTwig()->load('film.html.twig');
        echo $template->render([
            'oa' => $oa,
            'commentaires' => $commentaires
        ]);
    }
}

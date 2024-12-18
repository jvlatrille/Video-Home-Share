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

    // Vérifier que l'ID est un entier valide
    if (!is_numeric($idOa) || (int)$idOa <= 0) {
        die('ID du film invalide ou non spécifié.');
    }

    $idOa = (int)$idOa; // Convertir en entier

    $managerOa = new OADao();

    // Récupérer les informations du film
    $oa = $managerOa->find($idOa);

    if (!$oa) {
        die('Film non trouvé.');
    }

    // Utiliser l'ID TMDB récupéré depuis l'objet OA
    $idTMDB = $oa->getIdOa();

    // Récupérer les commentaires associés
    $commentaires = $managerOa->getCommentairesByTMDB($idTMDB);

    // Charger le template et afficher les données
    $template = $this->getTwig()->load('film.html.twig');
    echo $template->render([
        'oa' => $oa,
        'commentaires' => $commentaires
    ]);
}

}

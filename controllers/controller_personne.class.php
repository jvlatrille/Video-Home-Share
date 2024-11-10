<?php

require_once 'modeles/personne.dao.php';

class ControllerPersonne {
    private $personneDAO;

    public function __construct() {
        $this->personneDAO = new PersonneDAO();
    }

    // Afficher toutes les personnes
    public function afficherToutesPersonnes() {
        $personnes = $this->personneDAO->obtenirToutesPersonnes();
        include 'templates/personne.html.twig';  // Adapter le nom du fichier twig si nécessaire
    }

    // Afficher une personne spécifique
    public function afficherPersonne($idPersonne) {
        $personne = $this->personneDAO->obtenirPersonneParId($idPersonne);
        include 'templates/personne_detail.html.twig';  // Adapter le nom du fichier twig si nécessaire
    }

    // Ajouter une nouvelle personne
    public function ajouterPersonne($nom, $prenom, $dateNaiss, $genre) {
        $personne = new Personne(null, $nom, $prenom, $dateNaiss, $genre);
        return $this->personneDAO->ajouterPersonne($personne);
    }

    // Mettre à jour une personne existante
    public function mettreAJourPersonne($idPersonne, $nom, $prenom, $dateNaiss, $genre) {
        $personne = new Personne($idPersonne, $nom, $prenom, $dateNaiss, $genre);
        return $this->personneDAO->mettreAJourPersonne($personne);
    }

    // Supprimer une personne
    public function supprimerPersonne($idPersonne) {
        return $this->personneDAO->supprimerPersonne($idPersonne);
    }
}

?>

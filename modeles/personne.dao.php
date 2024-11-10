<?php

require_once 'bd.class.php';
require_once 'personne.class.php';

class PersonneDAO {
    private $db;

    public function __construct() {
        $this->db = new BD();
    }

    // Ajouter une personne
    public function ajouterPersonne($personne) {
        $query = $this->db->prepare('INSERT INTO personne (nom, prenom, dateNaiss, genre) VALUES (:nom, :prenom, :dateNaiss, :genre)');
        $query->bindValue(':nom', $personne->getNom());
        $query->bindValue(':prenom', $personne->getPrenom());
        $query->bindValue(':dateNaiss', $personne->getDateNaiss());
        $query->bindValue(':genre', $personne->getGenre());
        return $query->execute();
    }

    // Obtenir toutes les personnes
    public function obtenirToutesPersonnes() {
        $query = $this->db->query('SELECT * FROM personne');
        return $query->fetchAll(PDO::FETCH_CLASS, 'Personne');
    }

    // Obtenir une personne par ID
    public function obtenirPersonneParId($idPersonne) {
        $query = $this->db->prepare('SELECT * FROM personne WHERE idPersonne = :idPersonne');
        $query->bindValue(':idPersonne', $idPersonne);
        $query->execute();
        return $query->fetchObject('Personne');
    }

    // Mettre à jour une personne
    public function mettreAJourPersonne($personne) {
        $query = $this->db->prepare('UPDATE personne SET nom = :nom, prenom = :prenom, dateNaiss = :dateNaiss, genre = :genre WHERE idPersonne = :idPersonne');
        $query->bindValue(':nom', $personne->getNom());
        $query->bindValue(':prenom', $personne->getPrenom());
        $query->bindValue(':dateNaiss', $personne->getDateNaiss());
        $query->bindValue(':genre', $personne->getGenre());
        $query->bindValue(':idPersonne', $personne->getIdPersonne());
        return $query->execute();
    }

    // Supprimer une personne
    public function supprimerPersonne($idPersonne) {
        $query = $this->db->prepare('DELETE FROM personne WHERE idPersonne = :idPersonne');
        $query->bindValue(':idPersonne', $idPersonne);
        return $query->execute();
    }
}

?>

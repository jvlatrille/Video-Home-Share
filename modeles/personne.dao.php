<?php

/**
 * @file personne.dao.php
 * @author VINET LATRILLE Jules
 * @brief Classe PersonneDAO pour accéder à la base de données
 * @details Cette classe permet de gérer les personnes en base de données
 * @version 1.0
 * @date 17/11/2024
 */

class PersonneDAO
{
    /**
     * @brief Instance PDO pour la gestion de la base de données
     */
    private ?PDO $pdo;

    /**
     * @brief Constructeur de la classe PersonneDAO
     * @param PDO $pdo Instance de PDO pour la gestion de la base de données
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Getter pour l'instance PDO
     * @return PDO|null Instance PDO pour la gestion de la base de données
     */
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    /**
     * @brief Setter pour l'instance PDO
     * @param PDO|null $pdo Instance PDO pour la gestion de la base de données
     * @return void
     */
    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Récupérer une personne par ID
     * @param int|null $id Identifiant de la personne
     * @return Personne|null Objet Personne ou null si introuvable
     */
    public function find(?int $id): ?Personne
    {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "personne WHERE idPersonne = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['id' => $id]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $personneData = $pdoStatement->fetch();
        return $personneData ? $this->hydrate($personneData) : null;
    }

    /**
     * @brief Récupérer toutes les personnes
     * @return array Tableau d'objets Personne
     */
    public function findAll(int $limit = 50, int $offset = 0): array
    {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "personne LIMIT :limit OFFSET :offset";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $pdoStatement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);

        $resultats = $pdoStatement->fetchAll();

        // Si tu veux déboguer, tu peux utiliser var_dump ou logger ici
        if (empty($resultats)) {
            error_log("Aucun résultat trouvé pour la table personne.");
        }

        return $this->hydrateAll($resultats);
    }

    /**
     * @brief Hydrater un tableau associatif en objet Personne
     * @param array $tableauAssoc Tableau associatif contenant les données d'une personne
     * @return Personne|null Objet Personne ou null
     */
    private function hydrate(array $tableauAssoc): ?Personne
    {
        $personne = new Personne();
        $personne->setIdPersonne($tableauAssoc['IdPersonne'] ?? null); // Gérer les clés manquantes
        $personne->setNom($tableauAssoc['nom'] ?? null);
        $personne->setPrenom($tableauAssoc['prenom'] ?? null);
        $personne->setDateNaiss($tableauAssoc['dateNaiss'] ?? null);
        return $personne;
    }

    /**
     * @brief Hydrater une liste de tableaux associatifs en tableau d'objets Personne
     * @param array $resultats Liste de tableaux associatifs
     * @return array Tableau d'objets Personne
     */
    private function hydrateAll(array $resultats): array
    {
        $personnesListe = [];
        foreach ($resultats as $row) {
            $personnesListe[] = $this->hydrate($row);
        }
        return $personnesListe;
    }

    /**
     * @brief Ajouter une nouvelle personne en base de données
     * @param Personne $personne Objet Personne à ajouter
     * @return bool Succès ou échec de l'insertion
     */
    public function ajouterPersonne(Personne $personne): bool
    {
        $sql = 'INSERT INTO ' . PREFIXE_TABLE . 'personne (nom, prenom, dateNaiss) VALUES (:nom, :prenom, :dateNaiss)';
        $query = $this->pdo->prepare($sql);
        $query->bindValue(':nom', $personne->getNom());
        $query->bindValue(':prenom', $personne->getPrenom());
        $query->bindValue(':dateNaiss', $personne->getDateNaiss());
        return $query->execute();
    }

    /**
     * @brief Mettre à jour une personne en base de données
     * @param Personne $personne Objet Personne à mettre à jour
     * @return bool Succès ou échec de la mise à jour
     */
    public function mettreAJourPersonne(Personne $personne): bool
    {
        $sql = 'UPDATE ' . PREFIXE_TABLE . 'personne SET nom = :nom, prenom = :prenom, dateNaiss = :dateNaiss WHERE idPersonne = :idPersonne';
        $query = $this->pdo->prepare($sql);
        $query->bindValue(':nom', $personne->getNom());
        $query->bindValue(':prenom', $personne->getPrenom());
        $query->bindValue(':dateNaiss', $personne->getDateNaiss());
        $query->bindValue(':idPersonne', $personne->getIdPersonne());
        return $query->execute();
    }

    /**
     * @brief Supprimer une personne en base de données
     * @param int $idPersonne Identifiant de la personne à supprimer
     * @return bool Succès ou échec de la suppression
     */
    public function supprimerPersonne(int $idPersonne): bool
    {
        $sql = 'DELETE FROM ' . PREFIXE_TABLE . 'personne WHERE idPersonne = :idPersonne';
        $query = $this->pdo->prepare($sql);
        $query->bindValue(':idPersonne', $idPersonne);
        return $query->execute();
    }
}

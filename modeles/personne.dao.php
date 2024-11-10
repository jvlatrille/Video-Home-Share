<?php

class PersonneDAO {
    private ?PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Getters et setters pour PDO
    public function getPdo(): ?PDO {
        return $this->pdo;
    }

    public function setPdo(?PDO $pdo): void {
        $this->pdo = $pdo;
    }

    // Récupérer une personne par ID
    public function find(?int $id): ?Personne {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "personne WHERE idPersonne = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['id' => $id]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $personneData = $pdoStatement->fetch();
        return $personneData ? $this->hydrate($personneData) : null;
    }

    // Récupérer toutes les personnes
    public function findAll(): array {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "personne";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);

        $resultats = $pdoStatement->fetchAll();
        return $this->hydrateAll($resultats);
    }

    // Hydrate une personne à partir d'un tableau associatif
    private function hydrate(array $tableauAssoc): ?Personne {
        $personne = new Personne();
        $personne->setIdPersonne($tableauAssoc['idPersonne']);
        $personne->setNom($tableauAssoc['nom']);
        $personne->setPrenom($tableauAssoc['prenom']);
        $personne->setDateNaiss($tableauAssoc['dateNaiss']);
        $personne->setGenre($tableauAssoc['genre']);
        return $personne;
    }

    // Hydrate une liste de personnes
    private function hydrateAll(array $resultats): array {
        $personnesListe = [];
        foreach ($resultats as $row) {
            $personnesListe[] = $this->hydrate($row);
        }
        return $personnesListe;
    }

    // Ajouter une personne
    public function ajouterPersonne(Personne $personne): bool {
        $sql = 'INSERT INTO ' . PREFIXE_TABLE . 'personne (nom, prenom, dateNaiss, genre) VALUES (:nom, :prenom, :dateNaiss, :genre)';
        $query = $this->pdo->prepare($sql);
        $query->bindValue(':nom', $personne->getNom());
        $query->bindValue(':prenom', $personne->getPrenom());
        $query->bindValue(':dateNaiss', $personne->getDateNaiss());
        $query->bindValue(':genre', $personne->getGenre());
        return $query->execute();
    }

    // Mettre à jour une personne
    public function mettreAJourPersonne(Personne $personne): bool {
        $sql = 'UPDATE ' . PREFIXE_TABLE . 'personne SET nom = :nom, prenom = :prenom, dateNaiss = :dateNaiss, genre = :genre WHERE idPersonne = :idPersonne';
        $query = $this->pdo->prepare($sql);
        $query->bindValue(':nom', $personne->getNom());
        $query->bindValue(':prenom', $personne->getPrenom());
        $query->bindValue(':dateNaiss', $personne->getDateNaiss());
        $query->bindValue(':genre', $personne->getGenre());
        $query->bindValue(':idPersonne', $personne->getIdPersonne());
        return $query->execute();
    }

    // Supprimer une personne
    public function supprimerPersonne(int $idPersonne): bool {
        $sql = 'DELETE FROM ' . PREFIXE_TABLE . 'personne WHERE idPersonne = :idPersonne';
        $query = $this->pdo->prepare($sql);
        $query->bindValue(':idPersonne', $idPersonne);
        return $query->execute();
    }
}

?>

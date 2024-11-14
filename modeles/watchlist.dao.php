<?php

class WatchListDao {
    private ?PDO $pdo;

    public function __construct(PDO $pdo = null) {
        $this->pdo = $pdo;
    }

    // Fonction pour afficher une watchlist
    public function find(int $id): ?WatchList {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."watchlist WHERE idWatchlist = :id";
        
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['id' => $id]);
        $resultat = $pdoStatement->fetch(PDO::FETCH_ASSOC);

        if (!$resultat) {
            return null;
        }
        
        return $this->hydrate($resultat);
    }

    // Fonction pour afficher toutes les watchlists d'un utilisateur
    public function findAll(int $idUtilisateur): ?array {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."watchlist WHERE idUtilisateur = :id";
        
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['id' => $idUtilisateur]);
        $resultats = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

        if (!$resultats) {
            return null;
        }
        
        return $this->hydrateAll($resultats);
    }

    // Fonction pour afficher toutes les watchlists avec les films associés des autres utilisateurs (pour la page communauté)
    public function findAllVisibleWithFilms(int $idUtilisateur): array {
        $sqlWatchlists = "SELECT * FROM ".PREFIXE_TABLE."watchlist WHERE visible = 1 AND idUtilisateur != :id";
        $statementWatchlists = $this->pdo->prepare($sqlWatchlists);
        $statementWatchlists->execute(['id' => $idUtilisateur]);
        $watchlistsData = $statementWatchlists->fetchAll(PDO::FETCH_ASSOC);
    
        $watchlists = [];
    
        foreach ($watchlistsData as $data) {
            $watchlist = $this->hydrate($data);
            
            // Récupère les films pour chaque watchlist
            $sqlFilms = "SELECT o.* FROM ".PREFIXE_TABLE."constituer c
                         JOIN ".PREFIXE_TABLE."oa o ON c.idOA = o.idOA
                         WHERE c.idWatchlist = :idWatchlist";
            $statementFilms = $this->pdo->prepare($sqlFilms);
            $statementFilms->execute(['idWatchlist' => $watchlist->getIdWatchlist()]);
            $filmsData = $statementFilms->fetchAll(PDO::FETCH_ASSOC);
    
            foreach ($filmsData as $filmData) {
                $film = (new OADao($this->pdo))->hydrate($filmData);
                $watchlist->addOeuvre($film);
            }
    
            $watchlists[] = $watchlist;
        }
    
        return $watchlists;
    }

    // Fonction pour récupérer les films d'une watchlist
    private function recupererFilmsParWatchlistId(int $idWatchlist): array {
        $sql = "SELECT o.idOA, o.nom, o.note, o.type, o.description, o.dateSortie, o.vo, o.duree 
                FROM ".PREFIXE_TABLE."constituer c
                JOIN ".PREFIXE_TABLE."oa o ON c.idOA = o.idOA
                WHERE c.idWatchlist = :idWatchlist";
        
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['idWatchlist' => $idWatchlist]);
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fonction pour hydrater une watchlist avec ses films
    public function hydrateWithFilms(array $data): WatchList {
        $watchlist = $this->hydrate($data);

        $films = $this->recupererFilmsParWatchlistId($data['idWatchlist']);
        foreach ($films as $filmData) {
            $film = (new OADao($this->pdo))->hydrate($filmData);
            $watchlist->addOeuvre($film);
        }
    
        return $watchlist;
    }

    // Fonction pour hydrater une watchlist
    public function hydrate(array $data): WatchList {
        $watchlist = new WatchList();
        $watchlist->setIdWatchlist($data['idWatchlist']);
        $watchlist->setTitre($data['titre']);
        $watchlist->setGenre($data['genre']);
        $watchlist->setDescription($data['description']);
        $watchlist->setVisible($data['visible']);
        return $watchlist;
    }

    // Fonction pour hydrater plusieurs watchlists
    public function hydrateAll(array $resultats): array {
        $watchlistListe = [];
        foreach ($resultats as $row) {
            $watchlistListe[] = $this->hydrate($row);
        }
        return $watchlistListe;
    }

    // Fonction pour récupérer toutes les watchlists visibles n'appartenant pas à l'utilisateur
    public function findAllVisible(int $idUtilisateur): ?array {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."watchlist WHERE visible = 1 AND idUtilisateur != :id";
        
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['id' => $idUtilisateur]);
        $resultats = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

        if (!$resultats) {
            return null;
        }
        
        return $this->hydrateAll($resultats);
    }

    // Fonction pour créer une watchlist
    public function creerWatchlist(WatchList $watchlist): ?WatchList {
        $sql = "INSERT INTO ".PREFIXE_TABLE."watchlist (titre, genre, description, visible, idUtilisateur) 
                VALUES (:titre, :genre, :description, :visible, 1)"; // 1 pour les tests, normalement $_SESSION['idUtilisateur']
        
        try {
            $pdoStatement = $this->pdo->prepare($sql);
            $pdoStatement->execute([
                'titre' => $watchlist->getTitre(),
                'genre' => $watchlist->getGenre(),
                'description' => $watchlist->getDescription(),
                'visible' => $watchlist->getVisible()
            ]);

            $watchlist->setIdWatchlist($this->pdo->lastInsertId());
            return $watchlist;
        } catch (Exception $e) {
            error_log("Erreur lors de la création de la watchlist : " . $e->getMessage());
            return null;
        }
    }

    // Fonction pour supprimer une watchlist
    public function supprimerUneWatchlist(int $id, int $idUtilisateur): bool {
        $sql = "DELETE FROM ".PREFIXE_TABLE."watchlist WHERE idWatchlist = :id AND idUtilisateur = :idUtilisateur";
        
        try {
            $pdoStatement = $this->pdo->prepare($sql);
            $pdoStatement->execute(['id' => $id, 'idUtilisateur' => $idUtilisateur]);
            return true;
        } catch (Exception $e) {
            error_log("Erreur lors de la suppression de la watchlist : " . $e->getMessage());
            return false;
        }
    }

    // Fonction pour ajouter une OA à une watchlist
    public function ajouterOA(int $idWatchlist, int $idOA): bool {
        $sql = "INSERT INTO ".PREFIXE_TABLE."constituer (idWatchlist, idOA) VALUES (:idWatchlist, :idOA)";
        
        try {
            $pdoStatement = $this->pdo->prepare($sql);
            $pdoStatement->execute(['idWatchlist' => $idWatchlist, 'idOA' => $idOA]);
            return true;
        } catch (Exception $e) {
            error_log("Erreur lors de l'ajout de l'OA à la watchlist : " . $e->getMessage());
            return false;
        }
    }

    // Fonction pour supprimer une OA d'une watchlist
    public function supprimerOA(int $idWatchlist, int $idOA): bool {
        $sql = "DELETE FROM ".PREFIXE_TABLE."constituer WHERE idWatchlist = :idWatchlist AND idOA = :idOA";
        
        try {
            $pdoStatement = $this->pdo->prepare($sql);
            $pdoStatement->execute(['idWatchlist' => $idWatchlist, 'idOA' => $idOA]);
            return true;
        } catch (Exception $e) {
            error_log("Erreur lors de la suppression de l'OA de la watchlist : " . $e->getMessage());
            return false;
        }
    }

    // Fonction pour afficher les films d'une watchlist d'un utilisateur
    public function afficherFilmsWatchlist(int $idWatchlist): ?array {
        $sql = "SELECT o.* FROM ".PREFIXE_TABLE."constituer c
                JOIN ".PREFIXE_TABLE."oa o ON c.idOA = o.idOA
                WHERE c.idWatchlist = :id";
        
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['id' => $idWatchlist]);
        $resultats = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

        if (!$resultats) {
            return null;
        }
        
        $oaDao = new OADao($this->pdo);
        return $oaDao->hydrateAll($resultats);
    }
}

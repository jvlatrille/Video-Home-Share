<?php

/**
 * @file watchlist.dao.php
 * @author Thibault CHIPY 
 * @brief Classe WatchListDao pour accéder à la base de données et gérer les watchlists
 * @details Cette classe permet de gérer les watchlists en base de données 
 * 
 * @version 2.0
 * @date 24/11/2024
 */

class WatchListDao {

    /**
     * @brief instance de PDO
     *
     * @var PDO|null
     */
    private ?PDO $pdo;

    /**
     * @brief Constructeur de la classe WatchListDao
     * @param PDO $pdo : instance de PDO
     */
    public function __construct(PDO $pdo = null) {
        $this->pdo = $pdo;
    }

    // Fonction pour afficher une watchlist

    /**
     * @brief Fonction pour récupérer une Watchlist avec son identifiant
     *
     * @param integer $id identifiant de la watchlist
     * @return WatchList|null la Watchlist correspondant à l'identifiant ou null si non trouvée
     */
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
    /**
     * @brief Fonction pour récupérer toutes les watchlists d'un utilisateur
     *
     * @param integer $idUtilisateur identifiant de l'utilisateur
     * @return array|null la liste des watchlists de l'utilisateur ou null si non trouvée
     */
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
    /**
     * @brief Fonction pour récupérer toutes les Watchlists visibles avec les films associés des autres utilisateurs que l'utilisateur connecté
     *
     * @param integer $idUtilisateur identifiant de l'utilisateur connecté
     * @return array la liste des Watchlists visibles avec les films associés des autres utilisateurs que celui en paramètre
     */
    public function findAllVisibleWithFilms(int $idUtilisateur): array {
        $sqlWatchlists = "SELECT * FROM ".PREFIXE_TABLE."watchlist WHERE visible = 1 AND idUtilisateur != :id";
        $statementWatchlists = $this->pdo->prepare($sqlWatchlists);
        $statementWatchlists->execute(['id' => $idUtilisateur]);
        $watchlistsData = $statementWatchlists->fetchAll(PDO::FETCH_ASSOC);
    
        $watchlists = [];
    
        foreach ($watchlistsData as $data) {
            $watchlist = $this->hydrate($data);
            
            // Récupère les oeuvres pour chaque watchlist
            $oeuvres = "SELECT o.* FROM ".PREFIXE_TABLE."constituer c
                         JOIN ".PREFIXE_TABLE."oa o ON c.idOA = o.idOA
                         WHERE c.idWatchlist = :idWatchlist";
            $statementOeuvres = $this->pdo->prepare($oeuvres);
            $statementOeuvres->execute(['idWatchlist' => $watchlist->getIdWatchlist()]);
            $oas= $statementOeuvres->fetchAll(PDO::FETCH_ASSOC);
    
            foreach ($oas as $oa) {
                $oeuvre= (new OADao($this->pdo))->hydrate($oa);
                $watchlist->addOeuvre($oeuvre);
            }
    
            $watchlists[] = $watchlist;
        }
    
        return $watchlists;
    }

    // Fonction pour hydrater une watchlist
    /**
     * @brief Fonction pour hydrater une Watchlist
     *
     * @param array $data tableau associatif contenant les données de la Watchlist
     * @return WatchList la Watchlist hydratée sans les films
     */
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
    /**
     * @brief Fonction pour hydrater plusieurs Watchlists
     *
     * @param array $resultats tableau de tableaux associatifs contenant les données de plusieurs Watchlists
     * @return array la liste des Watchlists hydratées sans les films
     */
    public function hydrateAll(array $resultats): array {
        $watchlistListe = [];
        foreach ($resultats as $row) {
            $watchlistListe[] = $this->hydrate($row);
        }
        return $watchlistListe;
    }

    // Fonction pour récupérer toutes les watchlists visibles n'appartenant pas à l'utilisateur
    /**
     * @brief Fonction pour récupérer toutes les Watchlists visibles n'appartenant pas à l'utilisateur
     *
     * @param integer $idUtilisateur identifiant de l'utilisateur
     * @return array|null la liste des Watchlists visibles n'appartenant pas à l'utilisateur ou null si non trouvée
     */
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
    /**
     * @brief Fonction pour créer une Watchlist
     *
     * @param WatchList $watchlist la Watchlist à créer
     * @return WatchList|null la Watchlist créée ou null si erreur
     */
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

            //Vérifier si la watchlist contient des films, si oui les ajouter
            if ($watchlist->getListeOeuvres()) {
                foreach ($watchlist->getListeOeuvres() as $oeuvre) {
                    $this->ajouterOAWatchlist($watchlist->getIdWatchlist(), $oeuvre->getIdOA());
                }
            }

            return $watchlist;
        } catch (Exception $e) {
            error_log("Erreur lors de la création de la watchlist : " . $e->getMessage());
            return null;
        }
    }

    // Fonction pour supprimer une watchlist
    /**
     * @brief Fonction pour supprimer une Watchlist
     *
     * @param integer $id identifiant de la Watchlist
     * @param integer $idUtilisateur identifiant de l'utilisateur
     * @return bool true si la Watchlist a été supprimée, false sinon
     */
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
    /**
     * @brief Fonction pour ajouter une OA à une Watchlist
     *
     * @param integer $idWatchlist identifiant de la Watchlist
     * @param integer $idOA identifiant de l'OA
     * @return bool true si l'OA a été ajoutée à la Watchlist, false sinon
     */
    public function ajouterOAWatchlist(int $idWatchlist, int $idOA): bool {
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
    /**
     * @brief Fonction pour supprimer une OA d'une Watchlist
     *
     * @param integer $idWatchlist identifiant de la Watchlist
     * @param integer $idOA identifiant de l'OA
     * @return bool true si l'OA a été supprimée de la Watchlist, false sinon
     */
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
    /**
     * @brief Fonction pour afficher les films d'une Watchlist d'un utilisateur
     *
     * @param integer $idWatchlist identifiant de la Watchlist
     * @return array|null la liste des films de la Watchlist ou null si non trouvée
     */
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

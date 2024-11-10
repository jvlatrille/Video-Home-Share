<?php

class WatchListDao{
    private ?PDO $pdo;

    public function __construct(PDO $pdo=null){
        $this->pdo = $pdo;
    }

    //Fonction pour afficher une watchlist
    public function find(int $id): ?WatchList {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."watchlist w
        WHERE w.idWatchlist = :id";
        
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array('id' => $id));    
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = $pdoStatement->fetch(PDO::FETCH_ASSOC);  
        
        if (!$resultat) {
            // Si aucun résultat n'est trouvé
            var_dump("Aucune watchlist trouvée.");
            return null;
        }
        
        $watchlist = $this->hydrate($resultat);
        return $watchlist;
    }

    //Fonction pour afficher toutes les watchlists d'un utilisateur
    public function findAll(int $idUtilisateur): ?array {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."watchlist w
        WHERE w.idUtilisateur = :id";
        
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array('id' => $idUtilisateur));    
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $resultats = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);  
        
        if (!$resultats) {
            // Si aucun résultat n'est trouvé
            var_dump("Aucune watchlist trouvée.");
            return null;
        }
        
        $watchlist = $this->hydrateAll($resultats);
        return $watchlist;
    }
    




    public function hydrate(array $tableauAssoc) : ?WatchList{
        $watchlist=new WatchList();
        $watchlist->setIdWatchList($tableauAssoc['idWatchlist']);
        $watchlist->setTitre($tableauAssoc['titre']);
        $watchlist->setGenre($tableauAssoc['genre']);
        $watchlist->setDescription($tableauAssoc['description']);
        $watchlist->setVisible($tableauAssoc['visible']);
        return $watchlist;
    }

    public function hydrateAll(array $resultats): ?array {
        $watchlistListe = [];
        foreach ($resultats as $row) {
            $watchlistListe[] = $this->hydrate($row);
        }
        return $watchlistListe;
    }

    ///////////////////////////////////
    //TODO 08/11/2024
    //Fonction pour recuperer toutes les watchlists qui existent (seulement celle qui ont le champ visible à true) : fait
    //Fonction pour ceer une watchlist (avec un titre, un genre, une description et un champ visible) : fait
    //Fonction pour modifier une watchlist
    //Fonction pour supprimer une watchlist : fait
    //Fonction pour ajouter une OA à une watchlist
    //Fonction pour supprimer une OA d'une watchlist
    //Fonction pour partager une watchlist
    ///////////////////////////////////


    //Fonction pour recuperer toutes les watchlists qui existent (seulement celle qui ont le champ visible à true) 
    //et qui n'appartiennnt pas à l'utilisateur
    public function findAllVisible(int $idUtilisateur): ?array {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."watchlist w
        WHERE w.visible = 1 and w.idUtilisateur != :id";
        
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array('id' => $idUtilisateur));    
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $resultats = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);  
        
        if (!$resultats) {
            // Si aucun résultat n'est trouvé
            echo "Aucune watchlist trouvée.";
            return null;
        }
        
        $watchlist = $this->hydrateAll($resultats);
        return $watchlist;
    }

    //Fonction pour creer une watchlist
    public function creerWatchlist(WatchList $watchlist): ?WatchList {
        $sql = "INSERT INTO ".PREFIXE_TABLE."watchlist (titre, genre, description, visible, idUtilisateur) 
                VALUES (:titre, :genre, :description, :visible, 1)"; //1 pour les tests, normalement $_SESSION['idUtilisateur']
        
        try {
            $pdoStatement = $this->pdo->prepare($sql);
            $pdoStatement->execute(array(
                'titre' => $watchlist->getTitre(),
                'genre' => $watchlist->getGenre(),
                'description' => $watchlist->getDescription(),
                'visible' => $watchlist->getVisible(),
                //'idUtilisateur' => $watchlist->getIdUtilisateur()
            ));
            
            $watchlist->setIdWatchList($this->pdo->lastInsertId());
            return $watchlist;
        } catch (Exception $e) {
            // Gérer l'erreur (log, retour d'erreur, etc.)
            error_log("Erreur lors de la création de la watchlist : " . $e->getMessage());
            return null;
        }
    }

    //Fonction pour modifier une watchlist
    // public function modifierWatchlist(WatchList $watchlist): ?WatchList {
    //     $sql = "UPDATE ".PREFIXE_TABLE."watchlist SET titre = :titre, genre = :genre, description = :description, visible = :visible
    //             WHERE idWatchlist = 1"; //1 pour les tests, normalement $_SESSION['idUtilisateur']
        
    //     try {
    //         $pdoStatement = $this->pdo->prepare($sql);
    //         $pdoStatement->execute(array(
    //             'titre' => $watchlist->getTitre(),
    //             'genre' => $watchlist->getGenre(),
    //             'description' => $watchlist->getDescription(),
    //             'visible' => $watchlist->getVisible(),
    //             'id' => $watchlist->getIdWatchList()
    //         ));
            
    //         return $watchlist;
    //     } catch (Exception $e) {
    //         // Gérer l'erreur (log, retour d'erreur, etc.)
    //         error_log("Erreur lors de la modification de la watchlist : " . $e->getMessage());
    //         return null;
    //     }
    // }

    //Fonction pour supprimer une watchlist
    public function supprimerUneWatchlist(int $id, int $idUtilisateur): ?bool {
        $sql = "DELETE FROM ".PREFIXE_TABLE."watchlist WHERE idWatchlist = :id and idUtilisateur = :idUtilisateur";
        
        try {
            $pdoStatement = $this->pdo->prepare($sql);
            $pdoStatement->execute(array('id' => $id, 'idUtilisateur' => $idUtilisateur));
            return true;
        } catch (Exception $e) {
            error_log("Erreur lors de la suppression de la watchlist : " . $e->getMessage());
            return false;
        }
    }
    

    //Fonction pour ajouter une OA à une watchlist
    public function ajouterOA(int $idWatchlist, int $idOa): ?bool {
        $sql = "INSERT INTO ".PREFIXE_TABLE."constituer (idWatchlist, idOA) 
                VALUES (:idWatchlist, :idOA)";
        
        try {
            $pdoStatement = $this->pdo->prepare($sql);
            $pdoStatement->execute(array(
                'idWatchlist' => $idWatchlist,
                'idOA' => $idOa));
            return true;
        } catch (Exception $e) {
            error_log("Erreur lors de l'ajout de l'OA à la watchlist : " . $e->getMessage());
            return false;
        }
    }

}
<?php

/**
 * @file watchlist.dao.php
 * @author Thibault CHIPY 
 * @brief Classe WatchListDao pour accéder à la base de données et gérer les watchlists
 * @details Cette classe permet de gérer les watchlists en base de données 
 * 
 * @version 3.0
 * @date 30/12/2024
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

 
    /**
     * @brief Fonction pour récupérer une Watchlist avec son identifiant et les films associés
     * @details Cette fonction permet de récupérer une Watchlist avec son identifiant et les films associés
     *
     * @param integer $id identifiant de la watchlist à récupérer
     * @return ?Watchlist la Watchlist correspondant à l'identifiant ou null si non trouvée avec les films associés
     */
    public function findWithFilms(int $id): ?WatchList {
        $sql = "SELECT w.*, 
            GROUP_CONCAT(CONCAT(woa.idTMDB, ':', woa.type) SEPARATOR ', ') AS OAs
        FROM ".PREFIXE_TABLE."watchlist AS w
        JOIN ".PREFIXE_TABLE."watchlist_oa AS woa ON w.idWatchlist = woa.idWatchlist
        WHERE 
            w.idWatchlist = :id
        GROUP BY 
            w.idWatchlist;
    ";
            $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['id' => $id]);
        $resultat = $pdoStatement->fetch(PDO::FETCH_ASSOC);
        if (!$resultat) {
            return null;
        }
        //Si il y aucune oeuvre dans la watchlist
        if($resultat['OAs'] == null){
            $watchlist = $this->hydrate($resultat);
            return $watchlist;
        }

        $watchlist = $this->hydrate($resultat);

        $oeuvres = $this->recupererOeuvresParWatchlist($watchlist->getIdTMDB());

        $watchlist->setListeOeuvres($oeuvres);
        
        return $watchlist;
    }
    
    // Fonction pour afficher toutes les watchlists d'un utilisateur
    /**
     * @brief Fonction pour récupérer toutes les watchlists d'un utilisateur
     *
     * @param integer $idUtilisateur identifiant de l'utilisateur
     * @return array|null la liste des watchlists de l'utilisateur ou null si non trouvée
     */
    public function findAll(int $idUtilisateur): ?array {
        $sql = "SELECT w.*, 
        GROUP_CONCAT(CONCAT(woa.idTMDB, ':', woa.type) SEPARATOR ', ') AS OAs
    FROM ".PREFIXE_TABLE."watchlist AS w
    JOIN ".PREFIXE_TABLE."watchlist_oa AS woa ON w.idWatchlist = woa.idWatchlist
    WHERE 
        w.idUtilisateur = :id
    GROUP BY 
        w.idWatchlist;
";        
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['id' => $idUtilisateur]);
        $resultats = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

        if (!$resultats) {
            return null;
        }
        
        $watchlists = $this->hydrateAll($resultats);

        // Récupère les films pour chaque watchlist
        $this->ajouterOeuvresAuxWatchlists($watchlists);
   
        return $watchlists;
    }

   
    /**
     * @brief Fonction pour récupérer les oeuvres d'une Watchlist
     * @details Cette fonction permet de récupérer les oeuvres d'une Watchlist en séparant la chaîne IDTMDB des virgules 
     * pour extraire les identifiants des oeuvres
     *
     * @param integer $idWatchlist identifiant de la Watchlist
     * @return array la liste des oeuvres de la Watchlist
     */
    function recupererOeuvresParWatchlist(string $oaData): array {
        // Vérifie si le tableau est vide
        if (empty($oaData)) {
            return [];
        }
        $oaDao = new OADao($this->pdo);
        $oeuvres = [];
    
        
            foreach(explode(',', $oaData) as $oa) {
                $oa = explode(':', $oa);
                $idTMDB = $oa[0];
                $type = $oa[1];

            // Vérifie le type pour appeler la méthode correspondante
            if ($type === 'Film') {
                $oeuvre = $oaDao->find((int)$idTMDB); 
            } else {
                $oeuvre = $oaDao->findSerie((int)$idTMDB); 
            }
            
            if ($oeuvre !== null) {
                $oeuvres[] = $oeuvre;
            }
        }
        return $oeuvres;
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
        $watchlist->setIdTMDB($data['OAs']);
        $watchlist->setIdUtilisateur($data['idUtilisateur']);

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

    /**
     * @brief Fonction pour ajouter les oeuvres à une liste de Watchlists
     * @details Cette fonction permet d'ajouter les oeuvres à une liste de Watchlists
     * 
     * @param array $watchlists la liste des Watchlists
     * @return void
     */
    public function ajouterOeuvresAuxWatchlists(array $watchlists): void {
        $oaDao = new OADao($this->pdo);
        foreach ($watchlists as $watchlist) {
            $oeuvres = $this->recupererOeuvresParWatchlist($watchlist->getIdTMDB());
            $watchlist->setListeOeuvres($oeuvres);
        }
    }

    public function findOeuvresWatchlist(int $id): ?array {
        $sql = "SELECT w.*, 
            GROUP_CONCAT(CONCAT(woa.idTMDB, ':', woa.type) SEPARATOR ', ') AS OAs
        FROM ".PREFIXE_TABLE."watchlist AS w
        JOIN ".PREFIXE_TABLE."watchlist_oa AS woa ON w.idWatchlist = woa.idWatchlist
        WHERE 
            w.idWatchlist = :id
        GROUP BY 
            w.idWatchlist;
    ";
            $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['id' => $id]);
        $resultat = $pdoStatement->fetch(PDO::FETCH_ASSOC);
        if (!$resultat) {
            return null;
        }
        //Si il y aucune oeuvre dans la watchlist
        if($resultat['OAs'] == null){
            $watchlist = $this->hydrate($resultat);
            return $watchlist;
        }

        $watchlist = $this->hydrate($resultat);

        $oeuvres = $this->recupererOeuvresParWatchlist($watchlist->getIdTMDB());
        
        return $oeuvres;
    }


    // Fonction pour récupérer toutes les watchlists visibles n'appartenant pas à l'utilisateur
    /**
     * @brief Fonction pour récupérer toutes les Watchlists visibles n'appartenant pas à l'utilisateur
     *
     * @param integer $idUtilisateur identifiant de l'utilisateur
     * @return array|null la liste des Watchlists visibles n'appartenant pas à l'utilisateur ou null si non trouvée
     */
    public function findAllVisibleWithFilm(int $idUtilisateur): ?array {
        $sql = "SELECT w.*, 
            GROUP_CONCAT(CONCAT(woa.idTMDB, ':', woa.type) SEPARATOR ', ') AS OAs
        FROM ".PREFIXE_TABLE."watchlist AS w
        JOIN ".PREFIXE_TABLE."watchlist_oa AS woa ON w.idWatchlist = woa.idWatchlist
        WHERE 
            w.idUtilisateur != :id and w.visible = 1
        GROUP BY 
            w.idWatchlist;
    ";        
            $pdoStatement = $this->pdo->prepare($sql);
            $pdoStatement->execute(['id' => $idUtilisateur]);
            $resultats = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        
            if (!$resultats) {
                return null;
            }
        
            $watchlists = $this->hydrateAll($resultats);
            $managerOA = new OADao($this->pdo);
        
            $this->ajouterOeuvresAuxWatchlists($watchlists);
        
            return $watchlists;
        }
        

    /**
     * @brief Fonction pour créer une Watchlist
     *
     * @param WatchList $watchlist la Watchlist à créer
     * @return WatchList|null la Watchlist créée ou null si erreur
     * @bug la fonction ne créer pas la watchlist 
     */
    public function creerWatchlist(WatchList $watchlist): ?WatchList {
        $sql = "INSERT INTO ".PREFIXE_TABLE."watchlist (titre, genre, description, visible,idUtilisateur) 
                VALUES (:titre, :genre, :description, :visible,:id)"; 
        
        try {
            $pdoStatement = $this->pdo->prepare($sql);
            $pdoStatement->execute([
                'titre' => $watchlist->getTitre(),
                'genre' => $watchlist->getGenre(),
                'description' => $watchlist->getDescription(),
                'visible' => $watchlist->getVisible() ? 1 : 0,
                'id' => $watchlist->getIdUtilisateur()
            ]);

            $watchlist->setIdWatchlist($this->pdo->lastInsertId());
            return $watchlist;
        } catch (Exception $e) {
            error_log("Erreur lors de la création de la watchlist : " . $e->getMessage());
            return null;
        }
    }

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



        /**
         * @brief Modifier completement une watchlist
         * @details Cette fonction permet de modifier une Watchlist en mettant à jour les données de la Watchlist
         * 
         * @param WatchList $watchlist la Watchlist à modifier
         * @return bool true si la Watchlist a été modifiée, false sinon
         */
        public function modifierWatchlistComplete(WatchList $watchlist): bool {
            $sql = "UPDATE ".PREFIXE_TABLE."watchlist SET titre = :titre, genre = :genre, description = :description, visible = :visible WHERE idWatchlist = :idWatchlist";
            
            try {
                $pdoStatement = $this->pdo->prepare($sql);
                $result = $pdoStatement->execute([
                    'titre' => $watchlist->getTitre(),
                    'genre' => $watchlist->getGenre(),
                    'description' => $watchlist->getDescription(),
                    'visible' => (int)$watchlist->getVisible(),
                    'idWatchlist' => $watchlist->getIdWatchlist()
                ]);
                if (!$result) {
                    error_log("Échec de la mise à jour de la watchlist.");
                }
                return $result;
            } catch (Exception $e) {
                error_log("Erreur lors de la modification de la watchlist : " . $e->getMessage());
                var_dump($e->getMessage());
                return false;
            }
            
        }

    /**
     * @brief Fonction pour ajouter une OA à une Watchlist
     *
     * @param integer $idWatchlist identifiant de la Watchlist
     * @param integer $idTMDB identifiant TMDB de l'OA
     * @param mixed $type le type de l'OA (Film ou TV)
     * @return bool true si l'OA a été ajoutée à la Watchlist, false sinon
     */
    public function addOaToWatchlist(int $idWatchlist, int $idTMDB, string $type): void {
        $sql = "INSERT INTO " . PREFIXE_TABLE . "watchlist_oa (idWatchlist, idTMDB, type) 
                VALUES (:idWatchlist, :idTMDB, :type)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':idWatchlist' => $idWatchlist,
            ':idTMDB' => $idTMDB,
            ':type' => $type,
        ]);
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
        $sql = "DELETE FROM ".PREFIXE_TABLE."watchlist_oa WHERE idWatchlist = :idWatchlist AND idTMDB = :idOA";
        
        try {
            $pdoStatement = $this->pdo->prepare($sql);
            $pdoStatement->execute(['idWatchlist' => $idWatchlist, 'idOA' => $idOA]);
            return true;
        } catch (Exception $e) {
            error_log("Erreur lors de la suppression de l'OA de la watchlist : " . $e->getMessage());
            return false;
        }
    }


    public function calculGenreDominantWatchlist(array $oeuvres): ?string
    {
        $compteurGenres = [];
        $oaDao = new OADao($this->pdo);

        // Récupérer les genres de chaque oeuvre dans la watchlist
        foreach ($oeuvres as $oeuvre) {
            $idTMDB = $oeuvre->getIdOa();
            $type = $oeuvre->getType();

            // Vérifie le type pour appeler la méthode correspondante
            if ($type === 'Film') {
                $oa = $oaDao->find($idTMDB);
            } else {
                $oa = $oaDao->findSerie($idTMDB);
            }

            if ($oa) {
                $genres = $oa->getGenres();
                if (is_array($genres)) {
                    foreach ($genres as $genre) {
                        if (isset($compteurGenres[$genre])) {
                            $compteurGenres[$genre]++;
                        } else {
                            $compteurGenres[$genre] = 1;
                        }
                    }
                }
            }
        }

        // Retourner le genre avec le maximum d'occurrences
        return !empty($compteurGenres) ? array_search(max($compteurGenres), $compteurGenres) : null;
    }

    

}

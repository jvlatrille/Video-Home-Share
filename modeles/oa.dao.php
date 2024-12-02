<?php 

/**
 * @file oa.dao.php
 * @author Thibault CHIPY
 * @brief Classe OADao pour accéder à la base de données
 * @details Cette classe permet de gérer les oeuvres audiovisuelles en base de données 
 * 
 * @version 1.0
 * @date 13/11/2020
 */

class OADao{

    /**
     * @brief instance de PDO
     *
     * @var PDO|null
     */
    private ?PDO $pdo;

    /**
     * @brief Constructeur de la classe OADao
     * @param PDO $pdo : instance de PDO
     */
    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }


    //Getters et setters

    /**
     * @brief Retourne l'instance de PDO
     *
     * @return PDO|null
     */
    public function getPdo(): ?PDO{
        return $this->pdo;
    }

    /**
     * @brief Modifie l'instance de PDO
     *
     * @param PDO|null $pdo : instance de PDO
     * @return void
     */
    public function setPdo(?PDO $pdo): void{
        $this->pdo = $pdo;
    }

    /**
     * @brief Fonction pour recupérer une oeuvre audiovisuelle avec son identifiant
     *
     * @param integer|null $id Identifiant de l'OA
     * @return OA|null L'OA correspondant à l'identifiant ou null si non trouvé
     */
    public function find(?int $id): ?OA {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."oa WHERE idOA = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array('id' => $id));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $oaData = $pdoStatement->fetch();   
        return $oaData ? $this->hydrate($oaData) : null;
    }
    
    /**
     * @brief Fonction pour récupérer les genres d'une oeuvre audiovisuelle OA
     * 
     * @param integer $idOA Identifiant de l'oeuvre audiovisuelle OA
     * @return array|null Tableau de genres ou null si non trouvé
     */
    private function getTagsByOA(int $idOA): array {
        $sql = "SELECT t.nom
            FROM ".PREFIXE_TABLE."tag t
            JOIN ".PREFIXE_TABLE."posseder p ON p.idTag = t.idTag
            WHERE p.idOA = :idOA
        ";
        
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['idOA' => $idOA]);
        
        $tags = [];
        while ($row = $pdoStatement->fetch(PDO::FETCH_ASSOC)) {
            $tags[] = $row['nom'];
        }
        
        return $tags;
    }

    /**
     * @brief Fonction pour récupérer la collection d'une oeuvre audiovisuelle OA
     * 
     * @param int $idOa Identifiant de l'oeuvre audiovisuelle OA
     * @return Collection|null Collection de l'oeuvre audiovisuelle OA ou null si non trouvé
     */

     private function getCollectionByOA(int $idOA): ?string {
        $sql = "
            SELECT c.nom
            FROM vhs_collection c
            JOIN vhs_fairepartie f ON f.idCollection = c.idCollection
            WHERE f.idOA = :idOA
        ";

        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['idOA' => $idOA]);
        
        $collection = $pdoStatement->fetch(PDO::FETCH_ASSOC);
        return $collection ? $collection['nom'] : null;
    }
    
    //Fonction pour afficher sur la page d'acceuil les 10 OA les mieux notées
    /**
     * @brief Fonction pour recupérer les 10 oeuvres audiovisuelles les mieux notées
     *
     * @return array|null Tableau d'OA ou null si non trouvé
     */
    public function findMeilleurNote(): ?array {
        $sql = "SELECT distinct o.idOA,o.nom,o.note,o.type,o.description,o.dateSortie,o.vo,o.duree
         FROM ".PREFIXE_TABLE."oa o 
         JOIN ".PREFIXE_TABLE."posseder p ON o.idOA=p.idOA
         JOIN ".PREFIXE_TABLE."tag t ON p.idTag=t.idTag   
         ORDER BY o.note DESC LIMIT 5";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $topOas = [];
        while ($row = $pdoStatement->fetch(PDO::FETCH_ASSOC)) {
            // Hydrate un objet OA
            $oa = $this->hydrate($row);
            
            // Ajoute les tags et collections
            $oa->setGenres($this->getTagsByOA($oa->getIdOA()));
            $oa->setCollection($this->getCollectionByOA($oa->getIdOA()));

            $topOas[] = $oa;
        }
        
        return $topOas;
        
    }

    /*
    "SELECT o.nom,o.note,o.type,o.description,o.dateSortie,o.vo,o.duree,
                        t.nom,c.nom,c.type
         FROM ".PREFIXE_TABLE."oa o 
         JOIN ".PREFIXE_TABLE."posseder p ON o.idOA=p.idOA
         JOIN ".PREFIXE_TABLE."tag t ON p.idTag=t.idTag
         JOIN ".PREFIXE_TABLE."fairepartie f ON o.idOA=f.idOA
         JOIN ".PREFIXE_TABLE."collection c ON f.idCollection=c.idCollection         
         ORDER BY o.note DESC LIMIT 10";
    */

    //Méthode pour récupérer toutes les oeuvres audiovisuelles
    /**
     * @brief Fonction pour recupérer toutes les oeuvres audiovisuelles
     *
     * @return array|null Tableau d'OA ou null si non trouvé
     */
    public function findAll() {
        
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "oa limit 10";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);  

        $resultats = $pdoStatement->fetchAll();

        // Appelle hydrateAll pour transformer tous les enregistrements en objets OA
        return $this->hydrateAll($resultats);
    }
    /**
     * @brief Fonction pour hydrater un tableau associatif en objet OA
     *
     * @param array $tableauAssoc Tableau associatif contenant les données d'une OA 
     * @return OA|null L'objet OA ou null
     */
    public function hydrate(array $tableauAssoc) : ?OA{
        $oa=new OA();
        $oa->setIdOa($tableauAssoc['idOA']);
        $oa->setNom($tableauAssoc['nom']);
        $oa->setNote($tableauAssoc['note']);
        $oa->setType($tableauAssoc['type']);
        $oa->setDescription($tableauAssoc['description']);
        $oa->setDateSortie($tableauAssoc['dateSortie']);
        $oa->setVo($tableauAssoc['vo']);
        $oa->setDuree($tableauAssoc['duree']);
        return $oa;
    }

    /**
     * @brief Fonction pour hydrater un tableau de tableaux associatifs en tableau d'objets OA
     *
     * @param array $resultats Tableau de tableaux associatifs contenant les données de plusieurs OA
     * @return array|null Tableau d'objets OA ou null
     */
    public function hydrateAll(array $resultats): ?array {
        $oaListe = [];
        foreach ($resultats as $row) {
            $oaListe[] = $this->hydrate($row);
        }
        
        return $oaListe;
    }   
}
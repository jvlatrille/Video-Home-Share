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
    
    
    //Fonction pour afficher sur la page d'acceuil les 10 OA les mieux notées
    /**
     * @brief Fonction pour recupérer les 10 oeuvres audiovisuelles les mieux notées
     *
     * @return array|null Tableau d'OA ou null si non trouvé
     */
    public function findMeilleurNote(): ?array {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."oa ORDER BY note DESC LIMIT 10";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $resultats = $pdoStatement->fetchAll();
        return $this->hydrateAll($resultats);
    }

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
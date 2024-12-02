<?php
/**
 * @file collection.dao.php
 * @author Thibault CHIPY 
 * @brief Classe ColletcionDao sert à accéder à la base de données et gérer les collections des oeuvres audiovisuelles OA
 * @details Cette classe permet de gérer les collections en base de données 
 * 
 * @version 2.0
 * @date 25/11/2024
 */
class CollectionDao{

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
     * @brief Fonction pour recupérer une collection avec son identifiant
     * 
     * @param integer $id identifiant de la collection
     * @return Collection|null la collection correspondant à l'identifiant ou null si non trouvé
     */
    public function find(int $id): ?Collection {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."collection WHERE idCollection = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->bindValue(':id', $id, PDO::PARAM_INT);
        $pdoStatement->execute();
        $collection = $pdoStatement->fetch(PDO::FETCH_ASSOC);
    
        return $collection;
    }

    /**
     * @brief Fonction pour recupérer toutes les collections
     * 
     * @return array tableau de collections
     */

    public function findAll(): array {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."collection";
        $pdoStatement = $this->pdo->query($sql);
        $collections = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    
        return $collections;
    }

    /**
     * @brief Fonction pour recuperer le nom des collections et le type de la collection en fonction de l'id de la l'oeuvre audiovisuelle OA
     * 
     * @param integer $idOA identifiant de l'oeuvre audiovisuelle OA
     * @return array tableau de collections
     */
    public function findCollectionOA(int $idOA): array {
        $sql = "SELECT c.nom, c.type FROM ".PREFIXE_TABLE."collection c 
        JOIN ".PREFIXE_TABLE."fairepartie p ON c.idCollection = p.idCollection WHERE p.idOA = :idOA";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->bindValue(':idOA', $idOA, PDO::PARAM_INT);
        $pdoStatement->execute();
        $collections = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    
        return $collections;
    }


    /**
     * @brief Fonction pour trouver la collection parente d'une collection avec son identifiant
     * 
     * @param integer $id identifiant de la collection
     * 
     * @return Collection|null la collection parente correspondant à l'identifiant ou null si non trouvé
     */

    public function findCollectionParent(int $idCol): ?Collection {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."collection WHERE idCollection = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->bindValue(':id', $idCol, PDO::PARAM_INT);
        $pdoStatement->execute();
        $collection = $pdoStatement->fetch(PDO::FETCH_ASSOC);
    
        return $collection;
    }


}

<?php

/**
 * @file tag.dao.php
 * @author Thibault CHIPY 
 * @brief Classe TagDao sert à accéder à la base de données et gérer les genres des oeuvres audiovisuelles OA
 * @details Cette classe permet de gérer les tags Tag en base de données 
 * 
 * @version 1.0
 * @date 26/11/2024
 */

 class TagDao{
    
    /**
     * @brief instance de PDO
     *
     * @var PDO|null
     */
    private ?PDO $pdo;

    /**
     * @brief Constructeur de la classe TagDao
     * @param PDO $pdo : instance de PDO
     */
    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    /**
     * @brief Fonction pour recupérer un tag avec son identifiant
     * 
     * @param integer $id identifiant du tag
     * @return Tag|null le tag correspondant à l'identifiant ou null si non trouvé
     */
    public function find(int $id): ?Tag {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."tag WHERE idTag = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->bindValue(':id', $id, PDO::PARAM_INT);
        $pdoStatement->execute();
        $tag = $pdoStatement->fetch(PDO::FETCH_ASSOC);
    
        return $tag;
    }

    /**
     * @brief Fonction pour recupérer tous les tags
     * 
     * @return array tableau de tags
     */
    public function findAll(): array {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."tag";
        $pdoStatement = $this->pdo->query($sql);
        $tags = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    
        return $tags;
    }

    /**
     * @brief Fonction pour recupérer le nom des tags en fonction de l'id de l'oeuvre audiovisuelle OA
     * 
     * @param integer $idOA identifiant de l'oeuvre audiovisuelle OA
     * @return array tableau de tags
     */
    public function findTagsOA(int $idOA): array {
        $sql = "SELECT t.nom FROM ".PREFIXE_TABLE."tag t JOIN ".PREFIXE_TABLE."posseder p ON t.idTag = p.idTag WHERE p.idOA = :idOA";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->bindValue(':idOA', $idOA, PDO::PARAM_INT);
        $pdoStatement->execute();
        $tags = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    
        return $tags;
    }

 }
<?php 

class OADao{
    private ?PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }


    //Getters et setters
    public function getPdo(): ?PDO{
        return $this->pdo;
    }

    public function setPdo(?PDO $pdo): void{
        $this->pdo = $pdo;
    }

    public function find(?int $id): ?OA
    {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "OA WHERE idOA = :idOA";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->bindValue(':idOA', $id, PDO::PARAM_INT);
        $pdoStatement->execute();
    
        // Récupération de l'objet OA
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "OA");
        $oa = $pdoStatement->fetch();
    
        return $oa;
    }
    

    // //Méthode pour récupérer toutes les oeuvres audiovisuelles
    // public function findAll() {
    //     try {
    //         $sql = "SELECT * FROM " . PREFIXE_TABLE . "oa";
    //         $pdoStatement = $this->pdo->prepare($sql);
    //         $pdoStatement->execute();
    
    //         $resultats = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    
    //         $oaList = [];
    //         foreach ($resultats as $row) {
    //             // Crée un nouvel objet OA vide, puis utilise hydrate
    //             $oa = new OA();
    //             $oa->hydrate($row);
    //             $oaList[] = $oa;
    //         }
    
    //         return $oaList;
    //     } catch (PDOException $e) {
    //         echo "Erreur lors de la récupération des oeuvres : " . $e->getMessage();
    //         return [];
    //     }
    // }
    


    public function hydrate($tableauAssoc) : ?OA{
        $oa=new OA();
        $oa->setId($tableauAssoc['id']);
        $oa->setNom($tableauAssoc['nom']);
        $oa->setNote($tableauAssoc['note']);
        $oa->setType($tableauAssoc['type']);
        $oa->setDescription($tableauAssoc['description']);
        $oa->setDateSortie($tableauAssoc['dateSortie']);
        $oa->setVo($tableauAssoc['vo']);
        $oa->setLimiteAge($tableauAssoc['limiteAge']);
        $oa->setDuree($tableauAssoc['duree']);
        return $oa;
    }
    
    
}
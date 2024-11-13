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

    public function find(?int $id): ?OA {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."oa WHERE idOA = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array('id' => $id));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $oaData = $pdoStatement->fetch();   
        return $oaData ? $this->hydrate($oaData) : null;
    }
    
    

    //Méthode pour récupérer toutes les oeuvres audiovisuelles
    public function findAll() {
        
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "oa";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);  

        $resultats = $pdoStatement->fetchAll();

        // Appelle hydrateAll pour transformer tous les enregistrements en objets OA
        return $this->hydrateAll($resultats);
    }
    public function hydrate($tableauAssoc) : ?OA{
        $oa=new OA();
        $oa->setId($tableauAssoc['idOA']);
        $oa->setNom($tableauAssoc['nom']);
        $oa->setNote($tableauAssoc['note']);
        $oa->setType($tableauAssoc['type']);
        $oa->setDescription($tableauAssoc['description']);
        $oa->setDateSortie($tableauAssoc['dateSortie']);
        $oa->setVo($tableauAssoc['vo']);
        $oa->setDuree($tableauAssoc['duree']);
        return $oa;
    }

    public function hydrateAll(array $resultats): ?array {
        $oaListe = [];
        foreach ($resultats as $row) {
            $oaListe[] = $this->hydrate($row);
        }
        
        return $oaListe;
    }
    
    
    
}
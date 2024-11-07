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

    //Méthode pour récupérer toutes les oeuvres audiovisuelles
    public function findAll(){
        $sql="SELECT * FROM ".PREFIXE_TABLE."oa";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS| PDO::FETCH_PROPS_LATE, 'OA');
       $oa=$pdoStatement->fetchAll();
        return $oa;
    }
}
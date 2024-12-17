<?php
class messageDAO{
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

    //Méthode pour récupérer tout les messages d'un forum
    public function findAll(?int $idForum): ?array {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."message WHERE idForum = :idForum";

        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array('idForum' => $idUtilisateur));    
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $resultats = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);   
        if (!$resultats) {
            // Si aucun résultat n'est trouvé
            var_dump("Pas d'autres messages trouvés");
            return null;
        }
        $dataMessage =$this->hydrateAll($resultats);
        return $dataMessage;
    }
}
?>
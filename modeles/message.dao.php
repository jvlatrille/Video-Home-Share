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
        $pdoStatement->execute(array('idForum' => $idForum));    
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
    public function hydrateAll(array $resultats): ?array {
        $messageListe = [];
        foreach ($resultats as $row) {
            $messageListe[] = $this->hydrate($row);
        }
        
        return $messageListe;
    }
    public function hydrate($tableauAssoc) : ?Message{
        $message=new Message();
        $message->setIdMessage($tableauAssoc['idMessage']);
        $message->setContenu($tableauAssoc['contenu']);
        $message->setNbLikes($tableauAssoc['nbLike']);
        $message->setNbDislikes($tableauAssoc['nbDislike']);
        $message->setIdForum($tableauAssoc['idForum']);
        $message->setIdUtilisateur($tableauAssoc['idUtilisateur']);
        return $message;
    }
    public function incrementLike(int $idMessage): void
{
    $sql = "UPDATE ".PREFIXE_TABLE."message SET nbLike = nbLike + 1 WHERE idMessage = :idMessage";
    $query = $this->pdo->prepare($sql);
    $query->execute(['idMessage' => $idMessage]);
}

public function incrementDislike(int $idMessage): void
{
    $sql = "UPDATE ".PREFIXE_TABLE."message SET nbDislike = nbDislike + 1 WHERE idMessage = :idMessage";
    $query = $this->pdo->prepare($sql);
    $query->execute(['idMessage' => $idMessage]);
}

}
?>
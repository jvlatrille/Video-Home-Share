<?php 

class forumDAO{
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

    //Méthode pour récupérer un forum
    public function find(?int $idForum): ?Forum {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."forum WHERE idForum = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array('idForum' => $idForum));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $forumData = $pdoStatement->fetch();   
        return $forumData ? $this->hydrate($forumData) : null;
    }
    

    //Méthode pour récupérer tout les forums
    public function findAll() {
        
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "forum";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);  

        $resultats = $pdoStatement->fetchAll();

        // Appelle hydrateAll pour transformer tous les enregistrements en objets forum
        return $this->hydrateAll($resultats);
    }
    public function hydrate($tableauAssoc) : ?Forum{
        $forum=new Forum();
        $forum->setId($tableauAssoc['idForum']);
        $forum->setNom($tableauAssoc['nom']);
        $forum->setNote($tableauAssoc['description']);
        $forum->setType($tableauAssoc['theme']);
        return $forum;
    }

    public function hydrateAll(array $resultats): ?array {
        $forumListe = [];
        foreach ($resultats as $row) {
            $forumListe[] = $this->hydrate($row);
        }
        
        return $forumListe;
    }
    







}
?>
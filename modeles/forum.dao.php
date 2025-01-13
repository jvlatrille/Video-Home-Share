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
    public function listeForum(?int $idForum): ?Forum {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."forum";
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
        $forum->setIdForum($tableauAssoc['idForum']);
        $forum->setNom($tableauAssoc['nom']);
        $forum->setDescription($tableauAssoc['description']);
        $forum->setTheme($tableauAssoc['theme']);
        return $forum;
    }

    public function hydrateAll(array $resultats): ?array {
        $forumListe = [];
        foreach ($resultats as $row) {
            $forumListe[] = $this->hydrate($row);
        }
        
        return $forumListe;
    }

    public function find(int $idForum): ?Forum {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."forum WHERE idForum = :idForum";
        
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['idForum' => $idForum]);
        $resultat = $pdoStatement->fetch(PDO::FETCH_ASSOC);

        if (!$resultat) {
            return null;
        }
        
        return $this->hydrate($resultat);
    }

    //Fonction pour creer un forum
    public function creerForum(Forum $forum): ?Forum {
        $sql = "INSERT INTO ".PREFIXE_TABLE."forum (id, nom, description, theme, idUtilisateur) 
                VALUES (:id, :nom, :description, :theme, :idUtilisateur)";
        
        try {
            $pdoStatement = $this->pdo->prepare($sql);
            $pdoStatement->execute(array(
                'id' => $forum->getIdForum(),
                'nom' => $forum->getNom(),
                'description' => $forum->getDescription(),
                'theme' => $forum->getTheme(),
                'idUtilisateur' => $forum->getIdUtilisateur()
            ));
            
            $forum->setIdForum($this->pdo->lastInsertId());
            return $forum;
        } catch (Exception $e) {
            // Gérer l'erreur (log, retour d'erreur, etc.)
            error_log("Erreur lors de la création du forum : " . $e->getMessage());
            return null;
        }
    }

}
?>
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
        $message->setPseudo($tableauAssoc['pseudo']);
        $message->setPhotoProfil($tableauAssoc['photoProfil']);
        $message->setIdForum($tableauAssoc['idForum']);
        $message->setIdUtilisateur($tableauAssoc['idUtilisateur']);
        return $message;
    }

    //Fonction pour creer un message
    public function creerMessage(Message $message): ?Message {
        $sql = "INSERT INTO ".PREFIXE_TABLE."message (contenu, nbLike, nbDislike, pseudo, photoProfil idUtilisateur, idForum) 
                VALUES (:contenu, :nbLike, :nbDislike, :pseudo, :photoProfil, :idUtilisateur, :idForum)"; 
        
        try {
            $pdoStatement = $this->pdo->prepare($sql);
            $pdoStatement->execute(array(
                'contenu' => $message->getContenu(),
                'nbLike' => $message->getNbLikes(),
                'nbDislike' => $message->getNbDislikes(),
                'idUtilisateur' => $message->getIdUtilisateur(),
                'idForum' => $message->getIdForum()
            ));
            
            $message->setIdMessage($this->pdo->lastInsertId());
            return $message;
        } catch (Exception $e) {
            // Gérer l'erreur (log, retour d'erreur, etc.)
            error_log("Erreur lors de la création du message : " . $e->getMessage());
            return null;
        }
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



public function chargerAPropos(?int $idUtilisateur): ?array
    {
        $sql = "SELECT m.idMessage, m.contenu, m.nbLike, m.nbDislike, m.pseudo, m.photoProfil, f.nom FROM ".PREFIXE_TABLE."message m JOIN ".PREFIXE_TABLE."forum f ON m.idForum = f.idForum WHERE m.idUtilisateur = :idUtilisateur";

        
        try {
            $pdoStatement = $this->pdo->prepare($sql);
            $pdoStatement->execute(['idUtilisateur' => $idUtilisateur]);
            $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
            $resultats = $pdoStatement->fetchAll();

            if (empty($resultats)) {
                return null; // Aucun message trouvé
            }

            return $resultats;

           
        } catch (Exception $e) {
            error_log("Erreur lors de l'affichage des messages de l'utilisateur : " . $e->getMessage());
            return null;
        }
    }


}
?>
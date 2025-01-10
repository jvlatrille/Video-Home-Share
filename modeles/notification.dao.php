<?php 

class NotificationDao{
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

    //Méthode pour récupérer TOUTES les notifications d'une personne
    public function findAll(?string $idUtilisateur): ?array {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."notification WHERE idUtilisateur = :idUtilisateur ORDER BY dateNotif DESC";

        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array('idUtilisateur' => $idUtilisateur));    
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $resultats = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);   
        if (!$resultats) {
            // Si aucun résultat n'est trouvé
            return null;
        }
        $notifData =$this->hydrateAll($resultats);
        return $notifData;
    }

    //Méthode pour récupérer UNE notification d'un utilisateur
    public function findNotif(?int $idNotif): ?Notification {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."notification WHERE idNotif = :idNotif; UPDATE ".PREFIXE_TABLE."notification SET vu = 1 WHERE idNotif = :idNotif";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['idNotif' => $idNotif]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        
        $notifData = $pdoStatement->fetch();
        
        if ($notifData === false) {
            return null;  // Si aucune donnée n'est trouvée
        }
        // Hydrater et retourner l'objet Notification
        $resultat =  $this->hydrate($notifData);
        return $resultat;
    }
    

    // Méthode pour supprimer une notification d'un utilisateur
    public function supprimerUneNotification(?string $idNotif, ?int $idUtilisateur): ?bool {
        $sql = "DELETE FROM ".PREFIXE_TABLE."notification WHERE idNotif = :id AND idUtilisateur = :idUtilisateur";
            
            try {
                $pdoStatement = $this->pdo->prepare($sql);
                $pdoStatement->execute(['id' => $idNotif, 'idUtilisateur' => $idUtilisateur]);
                return true;
            } catch (Exception $e) {
                error_log("Erreur lors de la suppression de la notification : " . $e->getMessage());
                return false;
            }
    }

    // Méthode pour qu'un utilisateur supprime toutes ses notifications
    public function supprimerToutesLesNotifs(?int $idUtilisateur) {
        $sql = "DELETE FROM ".PREFIXE_TABLE."notification WHERE idUtilisateur = :idUtilisateur";
            
            try {
                $pdoStatement = $this->pdo->prepare($sql);
                $pdoStatement->execute(['idUtilisateur' => $idUtilisateur]);
                return true;
            } catch (Exception $e) {
                error_log("Erreur lors de la suppression des notifications : " . $e->getMessage());
                return false;
            }      
    }


    // // Méthode pour afficher tous les messages poster par un utilisateur
    // public function chargerAPropos(?int $idUtilisateur)
    // {
    //     //Méthode pour récupérer tout les messages d'un forum
    //     $sql = "SELECT * FROM ".PREFIXE_TABLE."message WHERE idForum = :idForum";

    //     $pdoStatement = $this->pdo->prepare($sql);
    //     $pdoStatement->execute(array('idForum' => $idForum));    
    //     $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
    //     $resultats = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);   
    //     if (!$resultats) {
    //         // Si aucun résultat n'est trouvé
    //         var_dump("Pas d'autres messages trouvés");
    //         return null;
    //     }
    //     $dataMessage =$this->hydrateAll($resultats);
    //     return $dataMessage;
    // }

    // Méthode pour récupérer tous les messages envoyés par un utilisateur
    public function chargerAPropos(?int $idUtilisateur): ?array
    {
        $sql = "SELECT m.idMessage, m.contenu, m.dateMessage, f.nomForum 
                FROM ".PREFIXE_TABLE."message m
                 INNER JOIN ".PREFIXE_TABLE."forum f ON m.idForum = f.idForum
                WHERE m.idUtilisateur = :idUtilisateur
                ORDER BY m.dateMessage DESC";

        try {
            $pdoStatement = $this->pdo->prepare($sql);
            $pdoStatement->execute(['idUtilisateur' => $idUtilisateur]);
            $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
            $resultats = $pdoStatement->fetchAll();

            if (!$resultats) {
                return null; // Aucun message trouvé
            }

            // Retourne les messages sous forme de tableaux associatifs
            return $resultats;

        } catch (Exception $e) {
            error_log("Erreur lors du chargement des messages de l'utilisateur : " . $e->getMessage());
            return null;
        }
    }

    

    public function hydrate($tableauAssoc) : ?Notification{
        $notif=new Notification();
        $notif->setIdNotif($tableauAssoc['idNotif']);
        $notif->setDateNotif($tableauAssoc['dateNotif']);
        $notif->setDestinataire($tableauAssoc['destinataire']);
        $notif->setContenu($tableauAssoc['contenu']);
        $notif->setVu($tableauAssoc['vu']);
        $notif->setIdUtilisateur($tableauAssoc['idUtilisateur']);
        return $notif;
    }

    public function hydrateAll(array $resultats): ?array {
        $notifListe = [];
        foreach ($resultats as $row) {
            $notifListe[] = $this->hydrate($row);
        }
        
        return $notifListe;
    }
    
    
    
}
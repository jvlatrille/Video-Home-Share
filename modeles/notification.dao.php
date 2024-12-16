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
    public function findAll(?int $idUtilisateur): ?array {
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

    //Méthode pour récupérer UNE notification d'une personne
    public function findNotif(?int $idNotif): ?Notification {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."notification WHERE idNotif = :idNotif";
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
    

     // Méthode pour compter le nombre total de notifications pour un utilisateur
     public function nbNotif(?string $idUtilisateur): ?int {
        $sql = "SELECT COUNT(*) FROM ".PREFIXE_TABLE."notification WHERE destinataire = :idUtilisateur";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['idUtilisateur' => $idUtilisateur]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $count = $pdoStatement->fetch();  
        return (int)$count;  // Retourne le nombre sous forme d'un entier
    }




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

    // public function supprimerSelection()
    // {
    //     if (isset($_POST['notifications']) && !empty($_POST['notifications'])) {
    //         $notificationsToDelete = $_POST['notifications']; // Un tableau contenant les IDs des notifications sélectionnées

    //         $managerNotif = new NotificationDao($this->getPdo());

    //         foreach ($notificationsToDelete as $idNotif) {
    //             $managerNotif->supprimerUneNotification((int)$idNotif, 1); // Utilisation de l'ID utilisateur par défaut (1) pour les tests
    //         }
    //     }
    // }


    // //Méthode pour qu'une personne supprime toutes ses notifications
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
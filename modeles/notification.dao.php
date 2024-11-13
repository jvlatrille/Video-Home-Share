<?php 

class notificationDao{
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

    //Méthode pour récupérer une notification
    public function find(?string $idNotif): ?Notification {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."notification WHERE idNotif = :notificationId";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array('notificationId' => $idNotif));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $notifData = $pdoStatement->fetch();   
        return $notifData ? $this->hydrate($notifData) : null;
    }

    //Méthode pour récupérer toutes les notifications d'une personne
    public function findForPers(?string $idUtilisateur): ?Notification {
      $sql = "SELECT * FROM ".PREFIXE_TABLE."notification WHERE  = :utilisateurId";
      $pdoStatement = $this->pdo->prepare($sql);
      $pdoStatement->execute(array('idUsr' => $idUtilisateur));
      $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
      $notifData = $pdoStatement->fetch();   
      return $notifData ? $this->hydrate($notifData) : null;
    }
    
    //Méthode pour calculer le nombre total de notification d'une personne
    public function nbNotif(?string $idNotif): ?Notification {
        $sql = "SELECT COUNT * FROM ".PREFIXE_TABLE."notification WHERE idNotif = :dateId";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array('dateId' => $dateNotif));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $notifData = $pdoStatement->fetch();   
        return $notifData ? $this->hydrate($notifData) : null;
    }

    //Méthode pour supprimer une notification
    public function supprimerUneNotif(?string $idNotif): ?Notification {
        $sql = "DELETE  FROM ".PREFIXE_TABLE."notification WHERE idNotif = :dateId";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array('dateId' => $idNotif));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $notifData = $pdoStatement->fetch();   
        return $notifData ? $this->hydrate($notifData) : null;
    }

    //Méthode pour qu'une personne supprime toutes ses notifications
    public function supprimerToutesLesNotifs(?string $idNotif): ?Notification {
        $sql = "DELETE  FROM ".PREFIXE_TABLE."notification WHERE idNotif = :dateId";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array('dateId' => $idNotif));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $notifData = $pdoStatement->fetch();   
        return $notifData ? $this->hydrate($notifData) : null;
    }

    public function hydrate($tableauAssoc) : ?Notification{
        $notif=new Notification();
        $notif->setIdNotif($tableauAssoc['idNotif']);
        $notif->setDateNotif($tableauAssoc['dateNotif']);
        $notif->setType($tableauAssoc['type']);
        $notif->setDestinataire($tableauAssoc['destinataire']);
        $notif->setContenu($tableauAssoc['contenu']);
        $notif->setVu($tableauAssoc['vu']);
        return $notif;
    }

    public function hydrateAll(array $resultats): ?array {
        $notifListe = [];
        foreach ($resultats as $row) {
            $notifListe[] = $this->hydrate($row);
        }
        
        return $oaListe;
    }
    
    
    
}
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

    //Méthode pour récupérer une notification
    public function findAll(?int $idNotif): ?array {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."notification WHERE notification.idUtilisateur = :dateId";

        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array('id' => $idUtilisateur));    
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $resultats = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);   

        if (!$resultats) {
            // Si aucun résultat n'est trouvé
            var_dump("Aucune watchlist trouvée.");
            return null;
        }

        $notifData =$this->hydrateAll($resultats);
        return $notifData;
    }

                                            

    //Méthode pour récupérer toutes les notifications d'une personne
    public function findForPers(?int $idNotif): ?Notification {
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

    // //Méthode pour supprimer une notification d'une personne
    public function supprimerUneNotif() {
        // Récupère l'ID de la notification à supprimer depuis l'URL
        $idNotif = isset($_GET['id']) ? $_GET['id'] : null;
        $idUtilisateur = 1; // Pour les tests, on utilise l'ID utilisateur 1, normalement $_SESSION['idUtilisateur']

        if ($idNotif === null) {
            // Gérer le cas où l'ID de la notification est manquant
            echo "Notification non spécifiée.";
            return;
        }

        // Supprimer la notification
        $managerNotif = new NotificationDao($this->getPdo());
        $managerNotif->supprimerUneNotif($idNotif); // Utilise la méthode correcte de suppression

        // Rediriger vers la liste des notifications
        header('Location: index.php?controleur=ControllerTestNotif&methode=listerNotif&id=' . $idUtilisateur);
        exit;
    }

    // //Méthode pour qu'une personne supprime toutes ses notifications
    public function supprimerToutesLesNotifs() {
        // Récupère l'ID de l'utilisateur depuis l'URL ou utilise une valeur par défaut
        $idUtilisateur = isset($_GET['id']) ? $_GET['id'] : 1; // Pour les tests, on utilise l'ID utilisateur 1

        // Supprimer toutes les notifications de cet utilisateur
        $managerNotif = new NotificationDao($this->getPdo());
        $managerNotif->supprimerToutesLesNotifs($idUtilisateur); // Utilise la méthode correcte de suppression

        // Rediriger vers la liste des notifications
        header('Location: index.php?controleur=ControllerTestNotif&methode=listerNotif&id=' . $idUtilisateur);
        exit;
    }

    public function hydrate($tableauAssoc) : ?Notification{
        $notif=new Notification();
        $notif->setIdNotif($tableauAssoc['idNotif']);
        $notif->setDateNotif($tableauAssoc['dateNotif']);
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
        
        return $notifListe;
    }
    
    
    
}
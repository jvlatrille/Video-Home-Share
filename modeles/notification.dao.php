<?php 

/**
 * @file notification.dao.php
 * @author DESPRE-HILDEVERT Léa
 * @brief Classe NotificationDao pour accéder aux notifications en base de données
 * @version 1.0
 * @date 
 */

class NotificationDao{

    /**
     * @brief Instance PDO pour l'accès à la base de données
     */
    private ?PDO $pdo;

     /**
     * @brief Constructeur de la classe NotificationDao
     * @param PDO $pdo Instance PDO pour la base de données
     */
    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

      /**
     * @brief Récupère toutes les notifications d'un utilisateur
     * @param int $idUtilisateur Identifiant de l'utilisateur
     * @return array Tableau d'objets Notification
     */
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

     /**
     * @brief Récupère une notification 
     * @param int $idNotif Identifiant de la notification
     * @return Notification|null
     */
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
    

    /**
    * @brief Supprime une notification 
    * @param string $idNotif Identifiant de la notification
    * @param int $idUtilisateur Identifiant de l'utilisateur
    * @return bool
    */
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

    /**
     * @brief Supprime toutes les notifications d'un utilisateur
     * @param int $idUtilisateur Identifiant de l'utilisateur
     */
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
    

    /**
     * @brief Hydrate un objet Notification à partir d'un tableau associatif
     * @param array $notif Données de la notification
     * @return Notification|null
     */
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

    /**
     * @brief Hydrate plusieurs objets Notification à partir d'un tableau de tableaux associatifs
     * @param array $resultats Données des notifications
     * @return array
     */
    public function hydrateAll(array $resultats): ?array {
        $notifListe = [];
        foreach ($resultats as $row) {
            $notifListe[] = $this->hydrate($row);
        }
        
        return $notifListe;
    }
    
    
    
}
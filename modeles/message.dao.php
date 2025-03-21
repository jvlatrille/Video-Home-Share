<?php
class messageDAO
{
    private ?PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    //Getters et setters
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    //Méthode pour récupérer tout les messages d'un forum
    public function findAll(?int $idForum): ?array {
        $sql = "SELECT m.idMessage, m.contenu, m.nbLike, m.nbDislike, m.idUtilisateur, m.idForum, u.pseudo, u.photoProfil
                FROM vhs_message m
                JOIN vhs_utilisateur u ON m.idUtilisateur = u.idUtilisateur
                WHERE idForum = :idForum";
                

        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array('idForum' => $idForum));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $resultats = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        if (!$resultats) {
            // Si aucun résultat n'est trouvé
            return null;
        }
        $dataMessage = $this->hydrateAll($resultats);
        return $dataMessage;
    }

    public function find(int $idMessage): ?Message
    {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "message WHERE idMessage = :idMessage";

        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['idMessage' => $idMessage]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = $pdoStatement->fetch();

        if (!$resultat) {
            return null;
        }

        return $this->hydrate($resultat);
    }

    

    public function hydrateAll(array $resultats): ?array
    {
        $messageListe = [];
        foreach ($resultats as $row) {
            $messageListe[] = $this->hydrate($row);
        }

        return $messageListe;
    }
    public function hydrate($tableauAssoc): ?Message
    {
        $message = new Message();
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
        $sql = "INSERT INTO ".PREFIXE_TABLE."message (contenu, nbLike, nbDislike, idUtilisateur, idForum) 
                VALUES (:contenu, :nbLike, :nbDislike, :idUtilisateur, :idForum)"; 
        
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

    public function modifierMessageDAO(Message $message): ?Message
    {
        $sql = "UPDATE ".PREFIXE_TABLE."message SET contenu = :contenu, nbLike = :nbLike, nbDislike = :nbDislike, pseudo = :pseudo, photoProfil = :photoProfil, idUtilisateur = :idUtilisateur, idForum = :idForum WHERE idMessage = :idMessage";
        try {
            $query = $this->pdo->prepare($sql);
            $query->execute([
                'contenu' => $message->getContenu(),
                'nbLike' => $message->getNbLikes(),
                'nbDislike' => $message->getNbDislikes(),
                'pseudo' => $message->getPseudo(),
                'photoProfil' => $message->getPhotoProfil(),
                'idUtilisateur' => $message->getIdUtilisateur(),
                'idForum' => $message->getIdForum(),
                'idMessage' => $message->getIdMessage()
            ]);
            return $message;
        } catch (Exception $e) {
            // Gérer l'erreur (log, retour d'erreur, etc.)
            error_log("Erreur lors de la modification du message : " . $e->getMessage());
            return null;
        }
    }

    public function supprimerMessageDAO(Message $message): ?Message
    {
        $sql = "DELETE FROM ".PREFIXE_TABLE."message WHERE idMessage = :idMessage";
        try {
            $query = $this->pdo->prepare($sql);
            $query->execute(['idMessage' => $message->getIdMessage()]);
            return $message;
        } catch (Exception $e) {
            // Gérer l'erreur (log, retour d'erreur, etc.)
            error_log("Erreur lors de la suppression du message : " . $e->getMessage());
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



    /**
     * @author Despré-Hildevert Léa
     * @brief Charger lka page APropos avec les messages écrits par l'utilisateur
     * @param int $idUtilisateur Identifiant de l'utilisateur
     * @return array
     */    
    public function chargerAPropos(?int $idUtilisateur): ?array
    {
        $sql = "SELECT m.idMessage, m.contenu, m.nbLike, m.nbDislike, f.nom FROM ".PREFIXE_TABLE."message m JOIN ".PREFIXE_TABLE."forum f ON m.idForum = f.idForum WHERE m.idUtilisateur = :idUtilisateur";
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

    /**
     * @author Despré-Hildevert Léa
     * @brief Créer une notification lorsqu'un message est liké ou disliké
     * @param int $idUtilisateur Identifiant de l'utilisateur
     * @param int $idMessage Identifiant du message
     * @param int $idForum Identifiant du forum
     * @param string $contenu Contenu de la notification
     * @return Notification
     */    
    public function creerNotif( ?int $idMessage, ?string $contenu): ?Notification
    {    // Récupérer l'idForum du message
        $sql1 = "SELECT idForum, idUtilisateur AS auteurMessage FROM vhs_message WHERE idMessage = :idMessage";
        $stmt = $this->pdo->prepare($sql1);
        $stmt->execute(['idMessage' => $idMessage]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            $idForum = $result['idForum'];
            $idUtilisateurAuteur = $result['auteurMessage'];
                
            // Créer une nouvelle instance de Notification avec l'idUtilisateur
            $notification = new Notification();
            $notification->setIdUtilisateur($idUtilisateurAuteur);

            // Insérer la notification
            $sql = "INSERT INTO ".PREFIXE_TABLE."notification ( dateNotif, destinataire, contenu, vu, idUtilisateur, idMessage, idForum)
                    VALUES ( :dateNotif, :destinataire, :contenu, :vu, :idUtilisateur, :idMessage, :idForum)";

            $currentDate = date('Y-m-d H:i:s'); //date et heure actuelle

            $pdoStatement = $this->pdo->prepare($sql);
            $pdoStatement->execute([
                'dateNotif' => $currentDate,
                'destinataire' =>NULL,
                'contenu' => $contenu,
                'vu' => 0,
                'idUtilisateur' => $idUtilisateurAuteur,
                'idMessage' => $idMessage,
                'idForum'=> $idForum
            ]);
        }

        // Récupérer l'ID de la notification insérée
        $notification->setIdNotif($this->pdo->lastInsertId());
       
        return $notification;
        
    }


    
    /**
     * @author VINET LATRILLE Jules
     * @brief Cette méthode récupère les messages les plus likés.
     * @param int $limit Le nombre maximum de messages à récupérer. Par défaut, 10.
     * @return array Un tableau de tableaux associatifs, chacun contenant les détails du message.
     */
    public function getTopLikedMessages(int $limit = 6): array
    {
        $sql = "SELECT 
                m.idMessage, 
                m.contenu, 
                m.nbLike, 
                u.pseudo, 
                u.photoProfil,
                f.nom as forumNom, 
                m.idForum
            FROM " . PREFIXE_TABLE . "message m
            JOIN " . PREFIXE_TABLE . "utilisateur u ON m.idUtilisateur = u.idUtilisateur
            JOIN " . PREFIXE_TABLE . "forum f ON m.idForum = f.idForum
            ORDER BY m.nbLike DESC
            LIMIT :limit";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $pdoStatement->execute();

        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * @author VINET LATRILLE Jules
     * @brief Cette méthode récupère les messages d'un utilisateur spécifique.
     * @param int $idUtilisateur L'identifiant de l'utilisateur.
     * @return array Un tableau de tableaux associatifs, chacun contenant les détails du message.
     */
    public function getMessagesByUser(int $idUtilisateur): array
    {
        $sql = "SELECT m.idMessage, m.contenu, m.nbLike, m.nbDislike, f.nom AS forumNom
            FROM ".PREFIXE_TABLE."message m 
            JOIN ".PREFIXE_TABLE."forum f ON m.idForum = f.idForum 
            WHERE m.idUtilisateur = :idUtilisateur 
            ORDER BY m.nbLike DESC";

        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['idUtilisateur' => $idUtilisateur]);
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }
}

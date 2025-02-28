<?php

/**
 * @file notification.class.php
 * @author DESPRE-HILDEVERT Léa
 * @brief 
 * @version 1.0
 * @date 
 */

class Notification{
    //Attributs d'une notification
    /**
    * @brief Identifiant unique de la notification
    */
    private ?int $idNotif;

    /**
    * @brief date de réçeption de la notification
    */
    private ?string $dateNotif;

    /**
    * @brief Nom de l'expediteur de la notification 
    * @remark il aurait fallut modifier destinataire par expéditeur
    */
    private ?string $destinataire;

    /**
    * @brief Contenu de la notification
    */
    private ?string $contenu;

    /**
    * @brief Booléen indiquant si la notification a été vu oui ou non
    */
    private ?bool $vu;

    /**
    * @brief Identifiant de l'utilisateur ayant reçu la notification
    */
    private ?int $idUtilisateur;

    /**
    * @brief Identifiant du message lié à la notification
    */
    private ?int $idMessage;

    /**
    * @brief Nom du forum d'où vient le message lié à la notification
    */
    private ?string $nomForum;

    
    /**
     * @brief Constructeur de la classe Notification
     * @param int|null $idNotif Identifiant de la notification
     * @param string|null $dateNotif Date de la notification
     * @param string|null $destinataire Pseudo de l'expéditeur de la notification
     * @param string|null $contenu Contenu de la notification
     * @param bool|false $vu Indique si la notification a été vu oui ou non
     * @param int|null $idUtilisateur Identifiant de l'utilisateur
     * @param int|null $idMessage Identifiant du message
     * @param string|null $nomForum Nom du forum
     */
    //Constructeur de la classe Notification
    public function __construct(?int $idNotif=null, ?string $dateNotif=null, ?string $destinataire=null, ?string $contenu=null, ?bool $vu=null, ?int $idUtilisateur=null, ?int $idMessage=null, ?string $nomForum=null){
        $this->idNotif=$idNotif;
        $this->dateNotif = $dateNotif;
        $this->destinataire = $destinataire;
        $this->contenu = $contenu;
        $this->vu = $vu ??false; //permet que vu ne soit jamais null
        $this->idUtilisateur=$idUtilisateur;
        $this->idMessage=$idMessage;
        $this->nomForum=$nomForum;
    }


    
    //Getters et setters de la classe Notification

    /**
     * @brief Retourne l'identifiant de la notification
     * @return int|null
     */
    public function getIdNotif(): ?int{
        return $this->idNotif;
    }

    /**
     * @brief Modifie l'identifiant de la notification
     * @param int|null $idNotif
     */
    public function setIdNotif(?int $idNotif): void{
        $this->idNotif = $idNotif;
    }

    /**
     * @brief Retourne la date de la notification
     * @return string|null
     */
    public function getDateNotif(): ?string{
        return $this->dateNotif;
    }

    /**
     * @brief Modifie la date de la notification
     * @param string|null $dateNotif
     */
    public function setDateNotif(?string $dateNotif): void{
        $this->dateNotif = $dateNotif;
    }

    /**
     * @brief Retourne le pseudo de l'expediteur
     * @return string|null
     */
    public function getDestinataire(): ?string{
        return $this->destinataire;
    }

    /**
     * @brief Modifie l'expediteur
     * @param string|null $destinataire
     */
    public function setDestinataire(?string $destinataire): void{
        $this->destinataire = $destinataire;
    }

    /**
     * @brief Retourne le contenu de la notification
     * @return string|null
     */
    public function getContenu(): ?string{
        return $this->contenu;
    }

        /**
     * @brief Modifie le contenu de la notification
     * @param string|null $contenu
     */
    public function setContenu(?string $contenu): void{
        $this->contenu = $contenu;
    }

    /**
     * @brief Retourne si oui ou non le notification a été vu
     * @return bool|false
     */
    public function getVu(): ?bool{
        return $this->vu ??false;
    }

        /**
     * @brief Modifie si oui ou non le notification a été vu
     * @param bool|false $vu
     */
    public function setVu(?bool $vu): void{
        $this->vu = $vu;
    }  

    /**
     * @brief Retourne l'identifiant de l'utilisateur
     * @return int|null
     */
    public function getIdUtilisateur(): ?int{
        return $this->idUtilisateur;
    }

        /**
     * @brief Modifie l'identifiant de l'utilisateur
     * @param int|null $idUtilisateur
     */
    public function setIdUtilisateur(?int $idUtilisateur): void{
        $this->idUtilisateur = $idUtilisateur;
    }

    
    /**
     * @brief Retourne l'identifiant du message
     * @return int|null
     */
    public function getIdMessage(): ?int{
        return $this->idMessage;
    }

        /**
     * @brief Modifie l'identifiant du message
     * @param int|null $idMessage
     */
    public function setIdMessage(?int $idMessage): void{
        $this->idMessage = $idMessage;
    }

     
    /**
     * @brief Retourne le nom du forum
     * @return string|null
     */
    public function getNomForum(): ?string{
        return $this->nomForum;
    }

        /**
     * @brief Modifie le nom du forum
     * @param string|null $nomForum
     */
    public function setNomForum(?string $nomForum): void{
        $this->nomForum = $nomForum;
    }

}

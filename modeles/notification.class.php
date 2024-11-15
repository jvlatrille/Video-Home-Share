<?php

class Notification{
    //Attributs d'une notification
    private ?int $idNotif;
    private ?string $dateNotif;
    private ?string $destinataire;
    private ?string $contenu;
    private ?bool $vu;

    //Constructeur de la classe Notification
    public function __construct(?int $idNotif=null, ?string $dateNotif=null, ?string $destinataire=null, ?string $contenu=null, ?bool $vu=null){
        $this->idNotif=$idNotif;
        $this->dateNotif = $dateNotif;
        $this->destinataire = $destinataire;
        $this->contenu = $contenu;
        $this->vu = $vu;
    }


    
    //Getters et setters de la classe Notification
    public function getIdNotif(): ?string{
        return $this->idNotif;
    }

    public function setIdNotif(?string $idNotif): void{
        $this->idNotif = $idNotif;
    }

    public function getDateNotif(): ?string{
        return $this->dateNotif;
    }

    public function setDateNotif(?string $dateNotif): void{
        $this->dateNotif = $dateNotif;
    }

    public function getDestinataire(): ?string{
        return $this->destinataire;
    }

    public function setDestinataire(?string $destinataire): void{
        $this->destinataire = $destinataire;
    }

    public function getContenu(): ?string{
        return $this->contenu;
    }

    public function setContenu(?string $contenu): void{
        $this->contenu = $contenu;
    }

    public function getVu(): ?bool{
        return $this->vu;
    }

    public function setVu(?bool $vu): void{
        $this->vu = $vu;
    }  

}

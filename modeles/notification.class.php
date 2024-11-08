<?php

class Notification{
    //Attributs d'une notification
    private ?string $dateNotif;
    private ?string $type;
    private ?string $destinataire;
    private ?string $contenu;
    private ?bool $vu;

    //Constructeur de la classe Notification
    public function __construct(?string $dateNotif=null, ?string $type=null, ?string $destinataire=null, ?string $contenu=null, ?bool $vu=null){
        $this->dateNotif = $dateNotif;
        $this->type = $type;
        $this->destinataire = $destinataire;
        $this->contenu = $contenu;
        $this->vu = $vu;
    }

    //Getters et setters de la classe Notification
    public function getDateNotif(): ?string{
        return $this->dateNotif;
    }

    public function setDateNotif(?string $dateNotif): void{
        $this->dateNotif = $dateNotif;
    }

    public function getType(): ?string{
        return $this->type;
    }

    public function setType(?string $type): void{
        $this->type = $type;
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
        $this->bool = $bool;
    }  

}

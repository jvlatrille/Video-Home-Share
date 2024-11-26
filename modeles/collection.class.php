<?php
class Collection{

    private ?int $idCollection;
    private ?string $nom;
    private ?string $type;
    private ?int $idCollectionParent;

    public function __construct(?int $id=null, ?string $nom=null, ?string $type=null, ?int $idCollectionParent=null){
        $this->idCollection = $id;
        $this->nom = $nom;
        $this->type = $type;
        $this->idCollectionParent = $idCollectionParentid;
    }

    public function getIdCollection(): ?int{
        return $this->idCollection;
    }

    public function setIdCollection(?int $id): void{
        $this->idCollection = $id;
    }

    public function getNom(): ?string{
        return $this->nom;
    }

    public function setNom(?string $nom): void{
        $this->nom = $nom;
    }

    public function getType(): ?string{
        return $this->type;
    }

    public function setType(?string $type): void{
        $this->type = $type;
    }

    public function getIdCollectionParent(): ?int{
        return $this->idCollectionParent;
    }

    public function setIdCollectionParent(?int $id): void{
        $this->idCollectionParent = $id;
    }


}
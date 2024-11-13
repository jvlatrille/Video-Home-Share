<?php

class JeuxDao {
    private ?PDO $pdo;

    public function __construct(?PDO $pdo = null) 
    {
        $this->pdo = $pdo;
    }

    public function getPdo(): ?PDO 
    {
        return $this->pdo;
    }

    public function setPdo($pdo): void 
    {
        $this->pdo = $pdo;
    }

    public function find(?int $idJeux)
    {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "jeu WHERE idJeu = :idJeu";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array('idJeu' => $idJeux));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $jeuxData = $pdoStatement->fetch();   
        return $jeuxData ? $this->hydrate($jeuxData) : null;
    }

    public function findAll()
    {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "jeu";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $allJeuxData = $pdoStatement->fetchAll();   
        return $this->hydrateAll($allJeuxData);
    }

    public function hydrate($tableauAssoc) : ?Jeux{
        $jeux = new Jeux();
        $jeux->setIdJeux($tableauAssoc['idJeu']);
        $jeux->setRegle($tableauAssoc['regle']);
        $jeux->setNom($tableauAssoc['nom']);
        return $jeux;
    }

    public function hydrateAll(array $liste): ?array {
        $jeuxListe = [];
        foreach ($liste as $jeu) {
            $jeuxListe[] = $this->hydrate($jeu);
        }
        
        return $jeuxListe;
    }
}
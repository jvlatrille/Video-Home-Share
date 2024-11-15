<?php

/**
 * Une classe pour représenter la 
 * @todo Revoir la définition de la Classe et si elle sera toujours utile
 */
class JeuxDao {
    private ?PDO $pdo;

    /**
     * Une fonction qui permet de construire la variable qui fait la connexion avec la base de données
     *
     * @param PDO|null $pdo
     */
    public function __construct(?PDO $pdo = null) 
    {
        $this->pdo = $pdo;
    }

    /**
     * Une fonction qui permet de récupérer la variable qui fait la connexion avec la base de données
     *
     * @return PDO|null
     */
    public function getPdo(): ?PDO 
    {
        return $this->pdo;
    }

    /**
     * Une fonction qui permet de modifier la variable qui fait la connexion avec la base de données
     *
     * @param  $pdo
     * @return void
     */
    public function setPdo($pdo): void 
    {
        $this->pdo = $pdo;
    }

    /**
     * Une fonction qui permet de récupérer un objet d'un Jeux spécifié avec idJeux
     *
     * @param integer|null $idJeux L'identifiant du Jeux que l'on veut récupérer
     * @return void
     */
    public function find(?int $idJeux)
    {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "jeu WHERE idJeu = :idJeu";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array('idJeu' => $idJeux));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $jeuxData = $pdoStatement->fetch();   
        return $jeuxData ? $this->hydrate($jeuxData) : null;
    }

    /**
     * Une fonction qui permet de récupérer l'ensemble des objets Jeux dans la base de données
     *
     * @return void
     */
    public function findAll()
    {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "jeu";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $allJeuxData = $pdoStatement->fetchAll();   
        return $this->hydrateAll($allJeuxData);
    }

    /**
     * Une fonction qui permet de créer un objet Jeux avec les données d'un requête
     *
     * @param  $tableauAssoc Le tableau qui contient les données de la requête à mettre dans l'objet
     * @return Jeux|null 
     */
    public function hydrate($tableauAssoc) : ?Jeux{
        $jeux = new Jeux();
        $jeux->setIdJeux($tableauAssoc['idJeu']);
        $jeux->setRegle($tableauAssoc['regle']);
        $jeux->setNom($tableauAssoc['nom']);
        return $jeux;
    }

    /**
     * Une fonction qui permet d'hydrater un tableau  
     *
     * @param array $liste
     * @return array|null
     */
    public function hydrateAll(array $liste): ?array {
        $jeuxListe = [];
        foreach ($liste as $jeu) {
            $jeuxListe[] = $this->hydrate($jeu);
        }
        
        return $jeuxListe;
    }
}
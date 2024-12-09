<?php

class UtilisateurDao
{
    private ?PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get the value of pdo
     */
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    /**
     * Set the value of pdo
     */
    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    // Récupérer un utilisateur par ID
    public function find(?int $id): ?Utilisateur {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "utilisateur WHERE idUtilisateur = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['id' => $id]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $utilisateur = $pdoStatement->fetch();
        return $utilisateur ? $this->hydrate($utilisateur) : null;
    }

    // Récupérer toutes les utilisateurs
    public function findAll(): array {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "utilisateur";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $utilisateur = $pdoStatement->fetchAll();
        return $this->hydrateAll($utilisateur);
    }

    // Hydrate un utilisateur à partir d'un tableau associatif
    private function hydrate(array $tableauAssoc): ?Utilisateur {
        $utilisateur = new Utilisateur();
        $utilisateur->setIdUtilisateur($tableauAssoc['idUtilisateur']);
        $utilisateur->setPseudo($tableauAssoc['pseudo']);
        $utilisateur->setPhotoProfil($tableauAssoc['photoProfil']);
        $utilisateur->setBanniereProfil($tableauAssoc['banniereProfil']);
        $utilisateur->setAdressMail($tableauAssoc['adressMail']);
        $utilisateur->setMotDePasse($tableauAssoc['motDePasse']);
        $utilisateur->setRole($tableauAssoc['role']);
        return $utilisateur;
    }

    // Hydrate une liste d'utilisateur
    private function hydrateAll(array $resul): array {
        $utilisateurListe = [];
        foreach ($resul as $ligne) {
            $utilisateurListe[] = $this->hydrate($ligne);
        }
        return $utilisateurListe;
    }

    //Changer un pseudo
    public function changerPseudo(?int $id, ?string $newPseudo): bool{
        $sql = "UPDATE vhs_utilisateur
                SET pseudo = :pseudo
                WHERE idUtilisateur = :id"; 
        $pdoStatement = $this->pdo->prepare($sql);
        $reussite = $pdoStatement->execute(['pseudo' => $newPseudo, 'id' => $id]);

        return $reussite;
    }

        //Changer un pseudo
        public function changerMail(?int $id, ?string $newMail): bool{
            $sql = "UPDATE vhs_utilisateur
                    SET adressMail = :mail
                    WHERE idUtilisateur = :id"; 
            $pdoStatement = $this->pdo->prepare($sql);
            $reussite = $pdoStatement->execute(['mail' => $newMail, 'id' => $id]);
    
            return $reussite;
        }


    /**
     * @brief Recherche un Utilisateur par son adresse mail
     * @author Thibault CHIPY
     * @param string $mail
     * @return Utilisateur|null
     */
    public function findByMail(?string $mail): ?Utilisateur {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "utilisateur WHERE adressMail = :mail";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['mail' => $mail]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $utilisateur = $pdoStatement->fetch();
        return $utilisateur ? $this->hydrate($utilisateur) : null;
    }
}
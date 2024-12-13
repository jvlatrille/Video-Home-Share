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
     * @brief Creer un Utilisateur par son adresse mail
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

    /**
     * @brief Creer un utilisateur en base de données
     * @author Thibault CHIPY 
     * @param Utilisateur $utilisateur
     * @return bool
     */
    public function creerUtilisateur(?Utilisateur $utilisateur): ?bool {
        $sql = "INSERT INTO " . PREFIXE_TABLE . "utilisateur (pseudo, photoProfil, banniereProfil, adressMail, motDePasse, role) 
                VALUES (:pseudo, :photoProfil, :banniereProfil, :adressMail, :motDePasse, :role)";
        $pdoStatement = $this->pdo->prepare($sql);
        $reussite = $pdoStatement->execute([
            'pseudo' => $utilisateur->getPseudo(),
            'photoProfil' => $utilisateur->getPhotoProfil(),
            'banniereProfil' => $utilisateur->getBanniereProfil(),
            'adressMail' => $utilisateur->getAdressMail(),
            'motDePasse' => $utilisateur->getMotDePasse(),
            'role' => $utilisateur->getRole()
        ]);
        return $reussite;
    }

    /**
     * @brief Vérifier si un utilisateur existe en base de données avec son adresse mail
     * @author Thibault CHIPY 
     * 
     * @param email de l'Utilisateur 
     * @return bool true si l'email existe, false sinon.
     */

     public function emailExiste(string $mail):bool{
        $sql="SELECT COUNT(adressMail) FROM vhs_utilisateur WHERE adressMail = :mail";
        $sqlStatement = $this->pdo->prepare($sql);
        $sqlStatement->execute(['mail' => $mail]);
        return $sqlStatement->fetchColumn() > 0;
     }


     /**
     * Vérifie si un mot de passe est robuste.
     *
     * Critères de robustesse :
     * - Longueur minimale de 8 caractères.
     * - Contient au moins une lettre majuscule (A-Z).
     * - Contient au moins une lettre minuscule (a-z).
     * - Contient au moins un chiffre (0-9).
     * - Contient au moins un caractère spécial (@$!%*?&).
     *
     * @param string $password Le mot de passe à valider.
     * @return bool true si le mot de passe respecte les critères, false sinon.
     */
    public function estRobuste(string $password): bool
    {
        $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';

        // La fonction preg_match retourne 1 si une correspondance est trouvée.
        return preg_match($regex, $password) === 1;
    }
}
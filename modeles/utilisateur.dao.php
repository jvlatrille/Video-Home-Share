<?php

/**
 * @file utilisateur.dao.php
 * @author LEVAL Noah
 * @brief Classe UtilisateurDao pour accéder aux notifications en base de données
 * @version 1.0
 * @date 
 */

class UtilisateurDao
{
    /**
     * @brief Instance PDO pour l'accès à la base de données
     */
    private ?PDO $pdo;

     /**
     * @brief Constructeur de la classe UtilisateurDao
     * @param PDO $pdo Instance PDO pour la base de données
     */
    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Getteur du pseudonyme de l'utilisateur
     *
     * @return ?PDO
     */
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

     /**
     * @brief Setteur du pseudonyme de l'utilisateur
     * @param PDO pdo
     * @return void
     */
    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Récupère un utilisateur spécifique par son identifiant
     * @param int|null $id Identifiant de l'utilisateur
     * @return Utilisateur|null Objet Utilisateur hydraté ou null si non trouvé
     */
    public function find(?int $id): ?Utilisateur {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "utilisateur WHERE idUtilisateur = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['id' => $id]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $utilisateur = $pdoStatement->fetch();
        return $utilisateur ? $this->hydrate($utilisateur) : null;
    }

    /**
     * @brief Récupère tous les utilisateurs
     * @return array Tableau d'objets Utilisateur
     */
    public function findAll(): array {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "utilisateur";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $utilisateur = $pdoStatement->fetchAll();
        return $this->hydrateAll($utilisateur);
    }

    /**
     * @brief Hydrate un objet Utilisateur à partir d'un tableau associatif
     * @param array $tableauAssoc Données utilisateur sous forme de tableau associatif
     * @return Utilisateur|null Objet Utilisateur hydraté ou null si échec
     */
    private function hydrate(array $tableauAssoc): ?Utilisateur {
        $utilisateur = new Utilisateur();
        $utilisateur->setIdUtilisateur($tableauAssoc['idUtilisateur']);
        $utilisateur->setPseudo($tableauAssoc['pseudo']);
        $utilisateur->setPhotoProfil($tableauAssoc['photoProfil']);
        $utilisateur->setBanniereProfil($tableauAssoc['banniereProfil']);
        $utilisateur->setAdressMail($tableauAssoc['adressMail']);
        $utilisateur->setMotDePasse($tableauAssoc['motDePasse']);
        $utilisateur->setRole($tableauAssoc['role']);
        $utilisateur->setBio($tableauAssoc['bio']);
        return $utilisateur;
    }

    /**
     * @brief Hydrate une liste d'objets Utilisateur
     * @param array $resul Liste des utilisateurs sous forme de tableaux associatifs
     * @return array Liste des objets Utilisateur hydratés
     */
    private function hydrateAll(array $resul): array {
        $utilisateurListe = [];
        foreach ($resul as $ligne) {
            $utilisateurListe[] = $this->hydrate($ligne);
        }
        return $utilisateurListe;
    }

    /**
     * @brief Change le pseudo d'un utilisateur
     * @param int|null $id Identifiant de l'utilisateur
     * @param string|null $newPseudo Nouveau pseudo à attribuer
     * @return bool Retourne true en cas de succès, false sinon
     */
    public function changerPseudo(?int $id, ?string $newPseudo): bool{
        $sql = "UPDATE " . PREFIXE_TABLE . "utilisateur
                SET pseudo = :pseudo
                WHERE idUtilisateur = :id"; 
        $pdoStatement = $this->pdo->prepare($sql);
        $reussite = $pdoStatement->execute(['pseudo' => $newPseudo, 'id' => $id]);

        return $reussite;
    }

    /**
     * @brief Change l'adresse email d'un utilisateur
     * @param int|null $id Identifiant de l'utilisateur
     * @param string|null $newMail Nouvelle adresse email à attribuer
     * @return bool Retourne true en cas de succès, false sinon
     */
    public function changerMail(?int $id, ?string $newMail): bool{
         $sql = "UPDATE " . PREFIXE_TABLE . "utilisateur
                 SET adressMail = :mail
                 WHERE idUtilisateur = :id"; 
        $pdoStatement = $this->pdo->prepare($sql);
        $reussite = $pdoStatement->execute(['mail' => $newMail, 'id' => $id]);
    
        return $reussite;
    }

    /**
     * @brief Change le mot de passe d'un utilisateur
     * @param int|null $id Identifiant de l'utilisateur
     * @param string|null $mdp Nouveau mot de passe à attribuer
     * @return bool Retourne true en cas de succès, false sinon
     */
    public function changerMdp(?int $id, ?string $mdp): bool{
        $sql = "UPDATE " . PREFIXE_TABLE . "utilisateur
                SET motDePasse = :mdp
                WHERE idUtilisateur = :id"; 
        $pdoStatement = $this->pdo->prepare($sql);
        $reussite = $pdoStatement->execute(['mdp' => $mdp, 'id' => $id]);
       
        return $reussite;
    }

    /**
     * @brief Change la bio d'un utilisateur
     * @param int|null $id Identifiant de l'utilisateur
     * @param string|null $bio Nouvelle bio à attribuer
     * @return bool Retourne true en cas de succès, false sinon
     */
    public function changerBio(?int $id, ?string $bio): bool{
         $sql = "UPDATE " . PREFIXE_TABLE . "utilisateur
                 SET bio = :bio
                 WHERE idUtilisateur = :id"; 
        $pdoStatement = $this->pdo->prepare($sql);
        $reussite = $pdoStatement->execute(['bio' => $bio, 'id' => $id]);
    
        return $reussite;
    }

    /**
     * @brief Met à jour la photo de profil d'un utilisateur
     * @param int $userId Identifiant de l'utilisateur
     * @param string $filePath Chemin du fichier de la nouvelle photo de profil
     * @return bool Retourne true en cas de succès, false sinon
     */
    public function updateUserPhoto(int $userId, string $filePath): bool{
        // Requête pour mettre à jour la photo de profil de l'utilisateur
        $sql = "UPDATE " . PREFIXE_TABLE . "utilisateur 
                SET photoProfil = :photoProfil 
                WHERE idUtilisateur = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $result = $pdoStatement->execute(['photoProfil' => $filePath, 'id' => $userId]);

        return $result;
    }

    /**
     * @brief Met à jour la bannière de profil d'un utilisateur
     * @param int $userId Identifiant de l'utilisateur
     * @param string $filePath Chemin du fichier de la nouvelle bannière
     * @return bool Retourne true en cas de succès, false sinon
     */
    public function updateUserBanniere(int $userId, string $filePath): bool{
        // Requête pour mettre à jour la photo de profil de l'utilisateur
        $sql = "UPDATE " . PREFIXE_TABLE . "utilisateur 
                SET banniereProfil = :banniere 
                WHERE idUtilisateur = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $result = $pdoStatement->execute(['banniere' => $filePath, 'id' => $userId]);

        return $result;
    }

    public function enregistrerTokenReset($userId, $token, $expiresAt)
    {
        // Prépare une requête SQL pour insérer ou mettre à jour le token
        $sql = "INSERT INTO " . PREFIXE_TABLE . "tokens (user_id, token, expires_at)
                VALUES (:user_id, :token, :expires_at)
                ON DUPLICATE KEY UPDATE
                    token = :token_update,
                    expires_at = :expires_at_update";

        $pdoStatement = $this->pdo->prepare($sql);
        $result = $pdoStatement->execute([':user_id' => $userId, ':token' => $token, ':expires_at' => $expiresAt, ':token_update' => $token, ':expires_at_update' => $expiresAt]);

        return $result;
    }

    public function supprimerToken($token)
    {
        $sql = "DELETE FROM " . PREFIXE_TABLE . "tokens 
                WHERE token = :token";

        $pdoStatement = $this->pdo->prepare($sql);
        $result = $pdoStatement->execute([':token' => $token]);
        
        return $result;
    }

    public function getTokenInfo($token)
    {
        $sql = "SELECT * FROM reset_tokens WHERE token = :token";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute([':token' => $token]);
    
        return $pdoStatement->fetch(PDO::FETCH_ASSOC);
    }

    public function getIdByToken($token)
    {
        $sql = "SELECT user_id FROM " . PREFIXE_TABLE . "tokens WHERE token = :token";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute([':token' => $token]);
    
        $result = $pdoStatement->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['user_id'] : null;
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
     * @brief Rechercher un Utilisateur par son pseudo
     * @author Jules VINET 
     * @param string $pseudo
     * @return Utilisateur|null
     */
    public function findByPseudo(?string $pseudo): ?Utilisateur {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "utilisateur WHERE pseudo = :pseudo";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['pseudo' => $pseudo]);
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
    public static function estRobuste(string $password): bool
    {
        $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';

        // La fonction preg_match retourne 1 si une correspondance est trouvée.
        return preg_match($regex, $password) === 1;
    }
}
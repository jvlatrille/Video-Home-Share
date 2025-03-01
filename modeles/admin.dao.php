<?php

/**
 * @file admin.dao.php
 * @author VINET LATRILLE Jules
 * @brief Gère les opérations d'administration liées aux utilisateurs dans la base de données (pour l'instant).
 * @version 1.0
 * @date 2025-01-11
 */
class AdminDao
{
    /** @var PDO|null $pdo Connexion à la base de données. */
    private ?PDO $pdo;

    /**
     * @brief Constructeur de la classe AdminDao.
     * @param PDO|null $pdo Instance PDO pour la connexion à la base de données.
     */
    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Récupère tous les utilisateurs de la base de données.
     * @return Utilisateur[] Tableau d'objets Utilisateur.
     */
    public function getAllUtilisateurs(): array
    {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "utilisateur";
        $stmt = $this->pdo->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $utilisateurs = $stmt->fetchAll();

        return $this->hydrateAll($utilisateurs);
    }

    /**
     * @brief Récupère un utilisateur par son ID.
     * @param int $idUtilisateur ID de l'utilisateur.
     * @return Utilisateur|null L'utilisateur correspondant ou null s'il n'existe pas.
     */
    public function getUtilisateurById(int $idUtilisateur): ?Utilisateur
    {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "utilisateur WHERE idUtilisateur = :idUtilisateur";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idUtilisateur' => $idUtilisateur]);
        $donnee = $stmt->fetch(PDO::FETCH_ASSOC);

        return $donnee ? $this->hydrate($donnee) : null;
    }

    /**
     * @brief Met à jour les informations d'un utilisateur
     *        (Uniquement pseudo, photo, bannière).
     *
     * @param int $idUtilisateur ID de l'utilisateur
     * @param string $pseudo Nouveau pseudo
     * @param string $photoProfil Nouveau chemin photo
     * @param string $banniereProfil Nouveau chemin bannière
     * @param string $role Nouveau rôle
     * @return bool True si la mise à jour a réussi, sinon false
     */
    public function adminModifierUtilisateur(
        int $idUtilisateur,
        string $pseudo,
        string $photoProfil,
        string $banniereProfil,
        string $role
    ): bool {
        $sql = "UPDATE " . PREFIXE_TABLE . "utilisateur
                SET pseudo         = :pseudo,
                    photoProfil    = :photoProfil,
                    banniereProfil = :banniereProfil,
                    role           = :role
                WHERE idUtilisateur = :idUtilisateur";
    
        $stmt = $this->pdo->prepare($sql);
    
        $params = [
            'pseudo'         => $pseudo,
            'photoProfil'    => $photoProfil,
            'banniereProfil' => $banniereProfil,
            'role'           => $role,
            'idUtilisateur'  => $idUtilisateur
        ];
    
        return $stmt->execute($params);
    }
        


    /**
     * @brief Supprime un utilisateur par son ID.
     * @param int $idUtilisateur ID de l'utilisateur à supprimer.
     * @return bool Retourne true si la suppression a réussi, sinon false.
     */
    public function supprimerUtilisateur(int $idUtilisateur): bool
    {
        $sql = "DELETE FROM " . PREFIXE_TABLE . "utilisateur WHERE idUtilisateur = :idUtilisateur";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['idUtilisateur' => $idUtilisateur]);
    }

    /**
     * @brief Hydrate un tableau de données en objets Utilisateur.
     * @param array $donnees Données récupérées depuis la base.
     * @return Utilisateur[] Tableau d'objets Utilisateur.
     */
    private function hydrateAll(array $donnees): array
    {
        $utilisateurs = [];
        foreach ($donnees as $donnee) {
            $utilisateurs[] = $this->hydrate($donnee);
        }
        return $utilisateurs;
    }

    /**
     * @brief Hydrate un tableau en un objet Utilisateur.
     * @param array $donnee Données de l'utilisateur.
     * @return Utilisateur Objet Utilisateur hydraté.
     */
    private function hydrate(array $donnee): Utilisateur
    {
        $utilisateur = new Utilisateur();
        $utilisateur->setIdUtilisateur($donnee['idUtilisateur']);
        $utilisateur->setPseudo($donnee['pseudo']);
        $utilisateur->setPhotoProfil($donnee['photoProfil']);
        $utilisateur->setBanniereProfil($donnee['banniereProfil']);
        $utilisateur->setAdressMail($donnee['adressMail']);
        $utilisateur->setMotDePasse($donnee['motDePasse']);
        $utilisateur->setRole($donnee['role']);
        return $utilisateur;
    }

    /**
     * @brief Récupère les logs de sauvegarde de la base de données.
     * @return array Tableau de logs de sauvegarde.
     */
    public function getBackupLogs(): array {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "derniereSave ORDER BY date_save DESC";
        $stmt = $this->pdo->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }
    
}

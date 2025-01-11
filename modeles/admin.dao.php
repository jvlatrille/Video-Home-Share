<?php

class AdminDao
{
    private ?PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    /**
     * Récupère tous les utilisateurs
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
     * Récupère un utilisateur par son ID
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
     * Met à jour un utilisateur dans la base de données
     */
    public function adminModifierUtilisateur(
        int $idUtilisateur,
        string $pseudo,
        string $photoProfil,
        string $banniereProfil,
        string $adressMail,
        ?string $motDePasse,
        string $role
    ): bool {
        $sql = "UPDATE " . PREFIXE_TABLE . "utilisateur 
                SET pseudo = :pseudo, 
                    photoProfil = :photoProfil, 
                    banniereProfil = :banniereProfil, 
                    adressMail = :adressMail, 
                    role = :role";

        // Ajouter la modification du mot de passe seulement si nécessaire
        if ($motDePasse !== null) {
            $sql .= ", motDePasse = :motDePasse";
        }

        $sql .= " WHERE idUtilisateur = :idUtilisateur";

        $stmt = $this->pdo->prepare($sql);

        $params = [
            'pseudo' => $pseudo,
            'photoProfil' => $photoProfil,
            'banniereProfil' => $banniereProfil,
            'adressMail' => $adressMail,
            'role' => $role,
            'idUtilisateur' => $idUtilisateur
        ];

        if ($motDePasse !== null) {
            $params['motDePasse'] = $motDePasse;
        }

        return $stmt->execute($params);
    }

    private function hydrateAll(array $donnees): array
    {
        $utilisateurs = [];
        foreach ($donnees as $donnee) {
            $utilisateurs[] = $this->hydrate($donnee);
        }
        return $utilisateurs;
    }

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
     * Supprime un utilisateur par son ID
     */
    public function supprimerUtilisateur(int $idUtilisateur): bool
    {
        $sql = "DELETE FROM " . PREFIXE_TABLE . "utilisateur WHERE idUtilisateur = :idUtilisateur";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['idUtilisateur' => $idUtilisateur]);
    }
}

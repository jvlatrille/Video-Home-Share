<?php

class AdminDao
{
    private ?PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    /**
     * Récupère tous les utilisateurs et les hydrate en objets Utilisateur
     */
    public function getAllUtilisateurs(): array
    {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "utilisateur";
        $stmt = $this->pdo->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $utilisateurs = $stmt->fetchAll();

        // ✅ Vérifie si des utilisateurs sont récupérés
        if (!$utilisateurs) {
            return [];
        }

        return $this->hydrateAll($utilisateurs);
    }


    /**
     * Permet à un administrateur de modifier toutes les informations d'un utilisateur
     */
    public function adminModifierUtilisateur(
        int $idUtilisateur,
        string $pseudo,
        string $photoProfil,
        string $banniereProfil,
        string $adressMail,
        string $motDePasse,
        string $role
    ): bool {
        $sql = "UPDATE " . PREFIXE_TABLE . "utilisateur 
                SET pseudo = :pseudo, 
                    photoProfil = :photoProfil, 
                    banniereProfil = :banniereProfil, 
                    adressMail = :adressMail, 
                    motDePasse = :motDePasse, 
                    role = :role 
                WHERE idUtilisateur = :idUtilisateur";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'pseudo' => $pseudo,
            'photoProfil' => $photoProfil,
            'banniereProfil' => $banniereProfil,
            'adressMail' => $adressMail,
            'motDePasse' => password_hash($motDePasse, PASSWORD_BCRYPT),
            'role' => $role,
            'idUtilisateur' => $idUtilisateur
        ]);
    }

    /**
     * Hydrate un tableau d'utilisateurs en objets Utilisateur
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
     * Hydrate une seule entrée en objet Utilisateur
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
}

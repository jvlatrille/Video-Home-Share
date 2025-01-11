<?php

class AdminDao
{
    private ?PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo;
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
}

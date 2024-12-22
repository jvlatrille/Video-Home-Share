<?php

require_once 'commentaire.class.php';

class CommentaireDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Récupère les commentaires pour un film
     * @param int $idTMDB
     * @return array
     */
    public function findByTMDB(int $idTMDB): array
    {
        $sql = "SELECT c.idCom, c.idTMDB, c.contenu, c.idUtilisateur, u.pseudo, u.photoProfil
                FROM vhs_commentaire c
                JOIN vhs_utilisateur u ON c.idUtilisateur = u.idUtilisateur
                WHERE c.idTMDB = :idTMDB";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idTMDB' => $idTMDB]);

        $commentaires = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $commentaires[] = new Commentaire(
                $row['idCom'],
                $row['idTMDB'],
                $row['contenu'],
                $row['idUtilisateur'],
                $row['pseudo'],
                $row['photoProfil']
            );
        }

        return $commentaires;
    }

    /**
     * @brief Trouve un commentaire par son ID
     * @param int $idCommentaire
     * @return Commentaire|null
     */
    public function findById(int $idCommentaire): ?Commentaire
    {
        $sql = "SELECT c.idCom, c.idTMDB, c.contenu, c.idUtilisateur, u.pseudo, u.photoProfil
                FROM vhs_commentaire c
                JOIN vhs_utilisateur u ON c.idUtilisateur = u.idUtilisateur
                WHERE c.idCom = :idCom";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idCom' => $idCommentaire]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Commentaire(
                $row['idCom'],
                $row['idTMDB'],
                $row['contenu'],
                $row['idUtilisateur'],
                $row['pseudo'],
                $row['photoProfil']
            );
        }

        return null;
    }

    /**
     * @brief Supprime un commentaire par son ID
     * @param int $idCommentaire
     * @return bool
     */
    public function supprimer(int $idCommentaire): bool
    {
        $sql = "DELETE FROM vhs_commentaire WHERE idCom = :idCom";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['idCom' => $idCommentaire]);
    }
}

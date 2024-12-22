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
        $sql = "SELECT c.idCom, c.idTMDB, c.contenu, c.dateCommentaire, c.idUtilisateur, u.pseudo, u.photoProfil
            FROM vhs_commentaire c
            JOIN vhs_utilisateur u ON c.idUtilisateur = u.idUtilisateur
            WHERE c.idTMDB = :idTMDB";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idTMDB' => $idTMDB]);

        $commentaires = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            error_log("DEBUG - Ligne récupérée : " . print_r($row, true));
            $commentaires[] = new Commentaire(
                $row['idCom'],
                $row['idTMDB'],
                $row['contenu'],
                $row['dateCommentaire'],
                (int)$row['idUtilisateur'], // Forcer en int ici
                $row['pseudo'],
                $row['photoProfil']
            );
        }

        return $commentaires;
    }


    /**
     * @brief Ajoute un commentaire dans la base de données
     * @param Commentaire $commentaire L'objet commentaire à ajouter
     * @return bool Retourne true si l'ajout est réussi, sinon false
     */
    public function ajouter(Commentaire $commentaire): bool
    {
        $date = date('Y-m-d'); // Date actuelle

        $sql = "INSERT INTO vhs_commentaire (idTMDB, contenu, dateCommentaire, idUtilisateur) 
            VALUES (:idTMDB, :contenu, :dateCommentaire, :idUtilisateur)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'idTMDB' => $commentaire->getIdTMDB(),
            'contenu' => $commentaire->getContenu(),
            'dateCommentaire' => $date,
            'idUtilisateur' => $commentaire->getIdUtilisateur()
        ]);
    }


    /**
     * @brief Trouve un commentaire par son ID
     * @param int $idCommentaire
     * @return Commentaire|null
     */
    public function findById(int $idCommentaire): ?Commentaire
    {
        $sql = "SELECT c.idCom, c.idTMDB, c.contenu, c.dateCommentaire, c.idUtilisateur, u.pseudo, u.photoProfil
            FROM vhs_commentaire c
            JOIN vhs_utilisateur u ON c.idUtilisateur = u.idUtilisateur
            WHERE c.idCom = :idCom";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idCom' => $idCommentaire]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            error_log("DEBUG - Données récupérées par findById : " . print_r($row, true));
            return new Commentaire(
                $row['idCom'],
                $row['idTMDB'],
                $row['contenu'],
                $row['dateCommentaire'],
                (int)$row['idUtilisateur'], // Conversion explicite en int
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

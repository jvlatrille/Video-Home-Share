<?php

require_once 'commentaire.class.php';

class CommentaireDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Récupérer les commentaires pour un film (par idTMDB)
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
            error_log("DEBUG - Commentaire trouvé : " . json_encode($row));
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




    // Ajouter un commentaire
    public function ajouter(Commentaire $commentaire): bool
    {
        $date = date('Y-m-d');
        $sql = "INSERT INTO vhs_commentaire (idTMDB, contenu, idUtilisateur) 
            VALUES (:idTMDB, :contenu, :idUtilisateur, :date)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'idTMDB' => $commentaire->getIdTMDB(),
            'contenu' => $commentaire->getContenu(),
            'idUtilisateur' => $commentaire->getIdUtilisateur()
        ]);
    }

    public function findById($idCommentaire)
    {
        $query = "SELECT * FROM commentaires WHERE id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$idCommentaire]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function supprimer(int $idCommentaire): bool
    {
        $sql = "DELETE FROM vhs_commentaire WHERE idCom = :idCom";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'idCom' => $idCommentaire
        ]);
    }
}

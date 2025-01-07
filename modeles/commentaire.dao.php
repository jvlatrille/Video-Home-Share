<?php
/**
 * @file commentaire.dao.php
 * @author VINET LATRILLE Jules
 * @brief Classe CommentaireDAO pour accéder aux commentaires en base de données
 * @version 1.0
  * @date 2024-12-22
 */

require_once 'commentaire.class.php';

class CommentaireDAO
{
    /**
     * @brief Instance PDO pour l'accès à la base de données
     */
    private PDO $pdo;

    /**
     * @brief Constructeur de la classe CommentaireDAO
     * @param PDO $pdo Instance PDO pour la base de données
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Hydrate un objet Commentaire à partir d'un tableau associatif
     * @param array $data Données du commentaire
     * @return Commentaire|null
     */
    private function hydrate(array $data): ?Commentaire
    {
        return new Commentaire(
            $data['idCom'] ?? null,
            $data['idTMDB'] ?? null,
            $data['contenu'] ?? null,
            $data['dateCommentaire'] ?? null,
            (int)($data['idUtilisateur'] ?? null),
            $data['pseudo'] ?? null,
            $data['photoProfil'] ?? null
        );
    }

    /**
     * @brief Hydrate plusieurs objets Commentaire à partir d'un tableau de tableaux associatifs
     * @param array $datas Données des commentaires
     * @return array
     */
    private function hydrateAll(array $datas): array
    {
        $commentaires = [];
        foreach ($datas as $data) {
            $commentaires[] = $this->hydrate($data);
        }
        return $commentaires;
    }

    /**
     * @brief Récupère les commentaires pour un film donné
     * @param int $idTMDB Identifiant TMDB du film
     * @return array Tableau d'objets Commentaire
     */
    public function findByTMDB(int $idTMDB): array
    {
        $sql = "SELECT c.idCom, c.idTMDB, c.contenu, c.dateCommentaire, c.idUtilisateur, u.pseudo, u.photoProfil
                FROM vhs_commentaire c
                JOIN vhs_utilisateur u ON c.idUtilisateur = u.idUtilisateur
                WHERE c.idTMDB = :idTMDB";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idTMDB' => $idTMDB]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->hydrateAll($rows);
    }

    /**
     * @brief Ajoute un commentaire dans la base de données
     * @param Commentaire $commentaire Objet Commentaire à ajouter
     * @return bool Retourne true si l'ajout est réussi, sinon false
     */
    public function ajouter(Commentaire $commentaire): bool
    {
        $sql = "INSERT INTO vhs_commentaire (idTMDB, contenu, dateCommentaire, idUtilisateur) 
                VALUES (:idTMDB, :contenu, :dateCommentaire, :idUtilisateur)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'idTMDB' => $commentaire->getIdTMDB(),
            'contenu' => $commentaire->getContenu(),
            'dateCommentaire' => date('Y-m-d'),
            'idUtilisateur' => $commentaire->getIdUtilisateur()
        ]);
    }

    /**
     * @brief Trouve un commentaire par son ID
     * @param int $idCommentaire Identifiant du commentaire
     * @return Commentaire|null
     */
    public function find(int $idCommentaire): ?Commentaire
    {
        $sql = "SELECT c.idCom, c.idTMDB, c.contenu, c.dateCommentaire, c.idUtilisateur, u.pseudo, u.photoProfil
                FROM vhs_commentaire c
                JOIN vhs_utilisateur u ON c.idUtilisateur = u.idUtilisateur
                WHERE c.idCom = :idCom";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idCom' => $idCommentaire]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    /**
     * @brief Récupère tous les commentaires
     * @return array Tableau d'objets Commentaire
     */
    public function findAll(): array
    {
        $sql = "SELECT c.idCom, c.idTMDB, c.contenu, c.dateCommentaire, c.idUtilisateur, u.pseudo, u.photoProfil
                FROM vhs_commentaire c
                JOIN vhs_utilisateur u ON c.idUtilisateur = u.idUtilisateur";

        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->hydrateAll($rows);
    }

    /**
     * @brief Supprime un commentaire par son ID
     * @param int $idCommentaire Identifiant du commentaire
     * @return bool
     */
    public function supprimer(int $idCommentaire): bool
    {
        $sql = "DELETE FROM vhs_commentaire WHERE idCom = :idCom";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['idCom' => $idCommentaire]);
    }
}

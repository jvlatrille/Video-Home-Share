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
            $data['photoProfil'] ?? null,
            $data["typeOA"] ?? null
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
     * @brief Récupère les commentaires pour une oeuvre donnée
     * @param int $idTMDB Identifiant TMDB du film
     * @return array Tableau d'objets Commentaire
     */
    public function findByTMDB(int $idTMDB,string $typeOA): array
    {
        $sql = "SELECT c.idCom, c.idTMDB,c.contenu, c.dateCommentaire, c.idUtilisateur, u.pseudo, u.photoProfil,c.typeOA
                FROM ".PREFIXE_TABLE."commentaire c
                JOIN ".PREFIXE_TABLE."utilisateur u ON c.idUtilisateur = u.idUtilisateur
                WHERE c.idTMDB = :idTMDB and c.typeOA = :typeOA"; ;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idTMDB' => $idTMDB,'typeOA' => $typeOA]);

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
        $sql = "INSERT INTO ".PREFIXE_TABLE."commentaire (idTMDB, contenu, dateCommentaire, idUtilisateur,typeOA) 
                VALUES (:idTMDB, :contenu, :dateCommentaire, :idUtilisateur,:typeOA)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'idTMDB' => $commentaire->getIdTMDB(),
            'contenu' => $commentaire->getContenu(),
            'dateCommentaire' => date('Y-m-d'),
            'idUtilisateur' => $commentaire->getIdUtilisateur(),
            'typeOA' => $commentaire->getType()
        ]);
    }

    /**
     * @brief Trouve un commentaire par son ID
     * @param int $idCommentaire Identifiant du commentaire
     * @return Commentaire|null
     */
    public function find(int $idCommentaire): ?Commentaire
    {
        $sql = "SELECT c.idCom, c.idTMDB,c.contenu, c.dateCommentaire, c.idUtilisateur, u.pseudo, u.photoProfil,c.typeOA
                FROM ".PREFIXE_TABLE."commentaire c
                JOIN ".PREFIXE_TABLE."utilisateur u ON c.idUtilisateur = u.idUtilisateur
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
        $sql = "SELECT c.idCom, c.idTMDB,c.contenu, c.dateCommentaire, c.idUtilisateur, u.pseudo, u.photoProfil,c.typeOA
                FROM ".PREFIXE_TABLE."commentaire c
                JOIN ".PREFIXE_TABLE."utilisateur u ON c.idUtilisateur = u.idUtilisateur";

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
        $sql = "DELETE FROM ".PREFIXE_TABLE."commentaire WHERE idCom = :idCom";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['idCom' => $idCommentaire]);
    }



    /**
     * @brief Affiche les commentaires de l'utilisateur connecté
     * @author Léa Despré-Hildevert
     * @param int $idUtilisateur Identifiant de l'utilisateur
     * @return array
     */
    public function chargerComm(?int $idUtilisateur): ?array
    {
        $sql = "SELECT c.idCom, c.idTMDB,c.contenu, c.dateCommentaire, c.idUtilisateur, u.pseudo, u.photoProfil,c.typeOA
                FROM ".PREFIXE_TABLE."commentaire c
                JOIN ".PREFIXE_TABLE."utilisateur u ON c.idUtilisateur = u.idUtilisateur
                WHERE c.idUtilisateur = :idUtilisateur";


        try {
            $pdoStatement = $this->pdo->prepare($sql);
            $pdoStatement->execute(['idUtilisateur' => $idUtilisateur]);
            $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
            $resultats = $pdoStatement->fetchAll();

            if (empty($resultats)) {
                return null; // Aucun message trouvé
            }

            return $resultats;
        } catch (Exception $e) {
            error_log("Erreur lors de l'affichage des commentaires de l'utilisateur : " . $e->getMessage());
            return null;
        }
    }

    public function findCommentairesByIdUtilisateur(int $idUtilisateur): array
    {
        $sql = "SELECT c.idCom, c.idTMDB,
                c.contenu, 
                c.dateCommentaire, 
                c.idUtilisateur, 
                u.pseudo, 
                u.photoProfil,c.typeOA
            FROM ".PREFIXE_TABLE."commentaire c
            JOIN ".PREFIXE_TABLE."utilisateur u ON c.idUtilisateur = u.idUtilisateur
            WHERE c.idUtilisateur = :idUtilisateur
            ORDER BY c.dateCommentaire DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idUtilisateur' => $idUtilisateur]);

        $commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Charger les infos des œuvres via OADao selon le type TV pour une série ou film
        $oaDao = new OADao($this->pdo);

        foreach ($commentaires as &$commentaire) {
            $tmdbId = $commentaire['idTMDB'];
            $type = $commentaire['typeOA'];
            
            if ($type === 'TV') {
                $oeuvre = $oaDao->findSerie($tmdbId);
                $commentaire['titreOeuvre'] = $oeuvre ? $oeuvre->getNom() : "Série inconnue";
            } else { 
                $oeuvre = $oaDao->find($tmdbId);
                $commentaire['titreOeuvre'] = $oeuvre ? $oeuvre->getNom() : "Titre inconnu";
            }
            
            $commentaire['backdropOeuvre'] = $oeuvre ? $oeuvre->getBackdropPath() : null;
        }

        return $commentaires;
    }

    /**
     * @brief Modifie un commentaire existant
     * @param int $idCom Identifiant du commentaire
     * @param string $contenu Nouveau contenu du commentaire
     * @return bool Retourne true si la modification est réussie, sinon false
     */
    public function modifier(int $idCom, string $contenu): bool
    {
        $sql = "UPDATE ".PREFIXE_TABLE."commentaire SET contenu = :contenu WHERE idCom = :idCom";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'contenu' => $contenu,
            'idCom' => $idCom
        ]);
    }
}

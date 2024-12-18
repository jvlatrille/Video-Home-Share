<?php

class ControllerCommentaire extends Controller
{
    public function ajouterCommentaire()
    {
        // Vérifier si les données nécessaires sont envoyées
        $contenu = $_POST['contenu'] ?? null;
        $idTMDB = $_POST['film_id'] ?? null;
        $idUtilisateur = $_POST['idUtilisateur'] ?? null;

        if (!$contenu || !$idTMDB || !$idUtilisateur) {
            // Redirection avec erreur si les données sont manquantes
            header("Location: index.php?controleur=oa&methode=afficherFilm&idOa=$idTMDB&erreur=1");
            exit();
        }

        // Ajouter le commentaire en base de données
        try {
            $manager = new OADao(); // Réutilisation du DAO pour la base de données
            $pdo = $manager->getConnection();
            $sql = "INSERT INTO vhs_commentaire (idTMDB, contenu, idUtilisateur) VALUES (:idTMDB, :contenu, :idUtilisateur)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'idTMDB' => $idTMDB,
                'contenu' => $contenu,
                'idUtilisateur' => $idUtilisateur,
            ]);

            // Redirection vers la page du film après l'ajout
            header("Location: index.php?controleur=oa&methode=afficherFilm&idOa=$idTMDB");
            exit();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout du commentaire : " . $e->getMessage());
            header("Location: index.php?controleur=oa&methode=afficherFilm&idOa=$idTMDB&erreur=1");
            exit();
        }
    }
}

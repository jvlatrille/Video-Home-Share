<?php

require_once 'modeles/commentaire.dao.php';
require_once 'modeles/oa.dao.php';

class ControllerCommentaire extends Controller
{
    public function ajouterCommentaire()
    {
        // Récupération des données du formulaire
        $idTMDB = $_POST['film_id'] ?? null;
        $contenu = $_POST['contenu'] ?? null;
        if (!isset($_SESSION['utilisateur'])) {
            error_log("ERREUR - SESSION utilisateur absente.");
            die("ERREUR : Utilisateur non connecté.");
        }

        // Désérialise si nécessaire
        $utilisateur = is_string($_SESSION['utilisateur']) ? unserialize($_SESSION['utilisateur']) : $_SESSION['utilisateur'];

        // Vérifie que la méthode existe
        if (!method_exists($utilisateur, 'getIdUtilisateur')) {
            error_log("ERREUR - Méthode getIdUtilisateur introuvable.");
            die("ERREUR : Impossible d'accéder à l'ID utilisateur.");
        }

        $idUtilisateur = $utilisateur->getIdUtilisateur();



        // Validation des champs
        if (!$idTMDB || !$contenu) {
            error_log("Champs manquants : idTMDB ou contenu");
            header("Location: index.php?controleur=oa&methode=afficherFilm&idOa=$idTMDB&erreur=1");
            exit();
        }

        try {
            // Connexion à la BD et ajout du commentaire
            $pdo = $this->getPdo(); // Méthode héritée du contrôleur parent
            $dao = new CommentaireDAO($pdo);

            $commentaire = new Commentaire(null, $idTMDB, $contenu, $idUtilisateur);
            $dao->ajouter($commentaire);

            error_log("Commentaire ajouté avec succès pour le film ID : $idTMDB par utilisateur ID : $idUtilisateur");
            header("Location: index.php?controleur=oa&methode=afficherFilm&idOa=$idTMDB");
            exit();
        } catch (PDOException $e) {
            error_log("Erreur PDO : " . $e->getMessage());
            header("Location: index.php?controleur=oa&methode=afficherFilm&idOa=$idTMDB&erreur=1");
            exit();
        }
    }
}

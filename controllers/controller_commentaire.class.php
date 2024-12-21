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

    public function supprimerCommentaire()
    {
        if (!isset($_SESSION['utilisateur'])) {
            error_log("ERREUR - Tentative de suppression sans utilisateur connecté");
            header('Location: index.php?controleur=authentification&methode=connexion');
            exit();
        }

        // Récupération des paramètres
        $idCommentaire = $_GET['idCommentaire'] ?? null;
        $idOa = $_GET['idOa'] ?? null;

        if (!$idCommentaire) {
            error_log("ERREUR - ID du commentaire manquant");
            die("ERREUR : ID du commentaire invalide.");
        }

        if (!$idOa) {
            error_log("ERREUR - ID du film manquant");
            die("ERREUR : ID du film invalide.");
        }

        $utilisateur = is_string($_SESSION['utilisateur']) ? unserialize($_SESSION['utilisateur']) : $_SESSION['utilisateur'];
        $pseudoUtilisateur = $utilisateur->getPseudo();

        if (!$idCommentaire || !$idOa) {
            error_log("ERREUR - ID du commentaire ou ID du film manquant");
            die("ERREUR : ID du commentaire ou ID du film invalide.");
        }

        try {
            $pdo = $this->getPdo();
            $dao = new CommentaireDAO($pdo);

            // Vérifier que le commentaire appartient à l'utilisateur
            $commentaire = $dao->findById($idCommentaire);

            if (!$commentaire || $commentaire['pseudo'] !== $pseudoUtilisateur) {
                error_log("ERREUR - Tentative de suppression d'un commentaire non autorisé");
                die("ERREUR : Vous n'êtes pas autorisé à supprimer ce commentaire.");
            }

            // Suppression via le DAO
            $resultat = $dao->supprimer($idCommentaire);

            if ($resultat) {
                error_log("Commentaire supprimé : ID $idCommentaire par utilisateur $pseudoUtilisateur");
                header("Location: index.php?controleur=oa&methode=afficherFilm&idOa=$idOa&succes=1");
                exit();
            } else {
                error_log("ERREUR - Échec de suppression du commentaire : ID $idCommentaire");
                header("Location: index.php?controleur=oa&methode=afficherFilm&idOa=$idOa&erreur=1");
                exit();
            }
        } catch (PDOException $e) {
            error_log("ERREUR PDO : " . $e->getMessage());
            header("Location: index.php?controleur=oa&methode=afficherFilm&idOa=$idOa&erreur=1");
            exit();
        }
    }
}

<?php

require_once 'modeles/commentaire.dao.php';
require_once 'modeles/oa.dao.php';

class ControllerCommentaire extends Controller
{
    public function ajouterCommentaire()
    {
        $idTMDB = $_POST['film_id'] ?? null;
        $contenu = $_POST['contenu'] ?? null;

        if (!isset($_SESSION['utilisateur'])) {
            error_log("ERREUR - SESSION utilisateur absente.");
            die("ERREUR : Utilisateur non connecté.");
        }

        // Désérialise si nécessaire
        $utilisateur = is_string($_SESSION['utilisateur']) ? unserialize($_SESSION['utilisateur']) : $_SESSION['utilisateur'];

        if (!$idTMDB || !$contenu) {
            error_log("ERREUR - Champs manquants : idTMDB ou contenu");
            header("Location: index.php?controleur=oa&methode=afficherFilm&idOa=$idTMDB&erreur=1");
            exit();
        }

        $idUtilisateur = $utilisateur->getIdUtilisateur();

        try {
            $pdo = $this->getPdo();
            $dao = new CommentaireDAO($pdo);

            $commentaire = new Commentaire(
                null,
                $idTMDB,
                $contenu,
                date('Y-m-d'), // Ajoute la date actuelle
                $idUtilisateur
            );

            if ($dao->ajouter($commentaire)) {
                error_log("Commentaire ajouté avec succès pour le film ID : $idTMDB par utilisateur ID : $idUtilisateur");
                header("Location: index.php?controleur=oa&methode=afficherFilm&idOa=$idTMDB&succes=1");
                exit();
            } else {
                error_log("ERREUR - Échec de l'ajout du commentaire.");
                header("Location: index.php?controleur=oa&methode=afficherFilm&idOa=$idTMDB&erreur=1");
                exit();
            }
        } catch (PDOException $e) {
            error_log("ERREUR PDO : " . $e->getMessage());
            header("Location: index.php?controleur=oa&methode=afficherFilm&idOa=$idTMDB&erreur=1");
            exit();
        }
    }


    public function supprimerCommentaire()
    {
        if (!isset($_SESSION['utilisateur'])) {
            die("ERREUR : Vous devez être connecté pour supprimer un commentaire.");
        }

        // Désérialiser si nécessaire
        $utilisateur = is_string($_SESSION['utilisateur']) ? unserialize($_SESSION['utilisateur']) : $_SESSION['utilisateur'];

        // Vérifier que l'utilisateur a bien une méthode getPseudo()
        if (!method_exists($utilisateur, 'getPseudo')) {
            error_log("ERREUR : L'objet utilisateur ne contient pas la méthode getPseudo().");
            die("ERREUR : Impossible de récupérer les informations de l'utilisateur.");
        }

        $idCommentaire = $_GET['idCommentaire'] ?? null;
        $idOa = $_GET['idOa'] ?? null;

        if (!$idCommentaire || !$idOa) {
            die("ERREUR : ID du commentaire ou ID du film invalide.");
        }

        $managerCommentaire = new CommentaireDAO($this->getPdo());
        $commentaire = $managerCommentaire->findById($idCommentaire);

        if (!$commentaire || $commentaire->getPseudo() !== $utilisateur->getPseudo()) {
            die("ERREUR : Vous n'êtes pas autorisé à supprimer ce commentaire.");
        }

        $managerCommentaire->supprimer($idCommentaire);

        header("Location: index.php?controleur=oa&methode=afficherFilm&idOa=$idOa&succes=1");
        exit();
    }
}

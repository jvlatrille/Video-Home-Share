<?php

/**
 * @file controller_commentaire.class.php
 * @author VINET LATRILLE Jules
 * @brief Contrôleur pour la gestion des commentaires
 * @version 1.0
 * @date 2024-12-22
 */
require_once 'modeles/commentaire.dao.php';
require_once 'modeles/oa.dao.php';

class ControllerCommentaire extends Controller
{
    /**
     * @brief Constructeur du contrôleur Commentaire
     * @param \Twig\Environment $twig Environnement Twig
     * @param \Twig\Loader\FilesystemLoader $loader Loader Twig
     */
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    /**
     * @brief Ajoute un commentaire pour un film
     * @return void
     */
    public function ajouterCommentaire()
    {
        $idTMDB = $_POST['film_id'] ?? null;
        $contenu = $_POST['contenu'] ?? null;

        if (!$this->utilisateurConnecte()) {
            die("ERREUR : Merci de vous connecter.");
        }

        if (!$idTMDB || !$contenu) {
            error_log("ERREUR - Champs manquants : idTMDB ou contenu");
            $this->redirectAvecErreur($idTMDB);
        }

        $utilisateur = $this->getUtilisateurConnecte();
        $idUtilisateur = $utilisateur->getIdUtilisateur();

        try {
            $dao = new CommentaireDAO($this->getPdo());

            $commentaire = new Commentaire(
                null,
                $idTMDB,
                $contenu,
                date('Y-m-d'),
                $idUtilisateur
            );

            if ($dao->ajouter($commentaire)) {
                error_log("Commentaire ajouté avec succès pour le film ID : $idTMDB par utilisateur ID : $idUtilisateur");
                $this->redirectAvecSucces($idTMDB);
            } else {
                error_log("ERREUR - Échec de l'ajout du commentaire.");
                $this->redirectAvecErreur($idTMDB);
            }
        } catch (PDOException $e) {
            error_log("ERREUR PDO : " . $e->getMessage());
            $this->redirectAvecErreur($idTMDB);
        }
    }

    /**
     * @brief Supprime un commentaire existant
     * @return void
     */
    public function supprimerCommentaire()
    {
        if (!$this->utilisateurConnecte()) {
            die("ERREUR : Vous devez être connecté pour supprimer un commentaire.");
        }

        $idCommentaire = $_GET['idCommentaire'] ?? null;
        $idOa = $_GET['idOa'] ?? null;

        if (!$idCommentaire || !$idOa) {
            die("ERREUR : ID du commentaire ou ID du film invalide.");
        }

        $utilisateur = $this->getUtilisateurConnecte();
        $managerCommentaire = new CommentaireDAO($this->getPdo());
        $commentaire = $managerCommentaire->find($idCommentaire);

        if (!$commentaire || $commentaire->getPseudo() !== $utilisateur->getPseudo()) {
            die("ERREUR : Vous n'êtes pas autorisé à supprimer ce commentaire.");
        }

        $managerCommentaire->supprimer($idCommentaire);

        $this->redirectAvecSucces($idOa);
    }

    /**
     * @brief Modifie un commentaire
     */
    public function modifierCommentaire()
    {
        if (!$this->utilisateurConnecte()) {
            die("ERREUR : Vous devez être connecté pour modifier un commentaire.");
        }

        $idCommentaire = $_POST['idCommentaire'] ?? null;
        $nouveauContenu = $_POST['contenu'] ?? null;

        if (!$idCommentaire || !$nouveauContenu) {
            die("ERREUR : Données invalides.");
        }

        $utilisateur = $this->getUtilisateurConnecte();
        $managerCommentaire = new CommentaireDAO($this->getPdo());
        $commentaire = $managerCommentaire->find($idCommentaire);

        if (!$commentaire || $commentaire->getIdUtilisateur() !== $utilisateur->getIdUtilisateur()) {
            die("ERREUR : Vous ne pouvez modifier que vos propres commentaires.");
        }

        if ($managerCommentaire->modifier($idCommentaire, $nouveauContenu)) {
            $this->redirectAvecSucces($commentaire->getIdTMDB());
        } else {
            $this->redirectAvecErreur($commentaire->getIdTMDB());
        }
    }

    /**
     * @brief Vérifie si un utilisateur est connecté
     * @return bool
     */
    private function utilisateurConnecte(): bool
    {
        return isset($_SESSION['utilisateur']);
    }

    /**
     * @brief Retourne l'utilisateur connecté
     * @return Utilisateur
     */
    private function getUtilisateurConnecte(): Utilisateur
    {
        return is_string($_SESSION['utilisateur'])
            ? unserialize($_SESSION['utilisateur'])
            : $_SESSION['utilisateur'];
    }

    /**
     * @brief Redirige avec un message d'erreur
     * @param int|null $idTMDB Identifiant du film
     */
    private function redirectAvecErreur(?int $idTMDB): void
    {
        header("Location: index.php?controleur=oa&methode=afficherFilm&idOa=$idTMDB&erreur=1");
        exit();
    }

    /**
     * @brief Redirige avec un message de succès
     * @param int|null $idTMDB Identifiant du film
     */
    private function redirectAvecSucces(?int $idTMDB): void
    {
        header("Location: index.php?controleur=oa&methode=afficherFilm&idOa=$idTMDB&succes=1");
        exit();
    }
}

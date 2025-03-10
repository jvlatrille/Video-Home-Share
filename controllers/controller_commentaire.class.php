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
        $idTMDB = $_POST['film_id'] ?? $_POST["serie_id"] ?? null;
        $contenu = $_POST['contenu'] ?? null;
        $type = $_POST['type'] ?? null;

        if (!$this->utilisateurConnecte()) {
            $this->afficherErreur("Vous devez être connecté pour ajouter un commentaire.");
        }        

        if (!$idTMDB || !$contenu || !$type) {
            $this->afficherErreur("Champs manquants : ID de l'œuvre, contenu ou type.");
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
                $idUtilisateur,
                null,
                null,
                $type
            );
// exit;
            if ($dao->ajouter($commentaire)) {
                error_log("Commentaire ajouté avec succès pour l'oeuvre , $type, ID : $idTMDB par utilisateur ID : $idUtilisateur");
                $this->redirectAvecSucces($idTMDB, $type);
            } else {
                error_log("ERREUR - Échec de l'ajout du commentaire.");
                $this->redirectAvecErreur($idTMDB,$type);
            }
        } catch (PDOException $e) {
            $this->afficherErreur("Une erreur est survenue lors de l'ajout du commentaire : " . $e->getMessage());
        }
        
    }



    /**
     * @brief Supprime un commentaire existant
     * @return void
     */
    public function supprimerCommentaire()
    {
        if (!$this->utilisateurConnecte()) {
            $this->afficherErreur("Vous devez être connecté pour supprimer un commentaire.");
        }        

        $idCommentaire = $_GET['idCommentaire'] ?? null;
        $idOa = $_GET['idOa'] ?? null;
        $type = $_GET["typeOA"] ?? null;

        if (!$idCommentaire || !$idOa) {
            $this->afficherErreur("ID du commentaire ou de l'œuvre invalide.");
        }        

        $utilisateur = $this->getUtilisateurConnecte();
        $managerCommentaire = new CommentaireDAO($this->getPdo());
        $commentaire = $managerCommentaire->find($idCommentaire);

        if (!$commentaire || ($commentaire->getIdUtilisateur() !== $utilisateur->getIdUtilisateur() && $utilisateur->getRole() !== 'admin')) {
            $this->afficherErreur("Vous n'êtes pas autorisé à supprimer ce commentaire.");
        }        

        $managerCommentaire->supprimer($idCommentaire);

        $this->redirectAvecSucces($idOa, $type);
    }

    /**
     * @brief Modifie un commentaire
     */
    public function modifierCommentaire()
    {
        if (!$this->utilisateurConnecte()) {
            $this->afficherErreur("Vous devez être connecté pour modifier un commentaire.");
        }        

        $idCommentaire = $_POST['idCommentaire'] ?? null;
        $nouveauContenu = $_POST['contenu'] ?? null;
        $type = $_POST["typeOA"] ?? null;

        if (!$idCommentaire || !$nouveauContenu || !$type) {
            $this->afficherErreur("Données invalides. Veuillez remplir tous les champs.");
        }        

        $utilisateur = $this->getUtilisateurConnecte();
        $managerCommentaire = new CommentaireDAO($this->getPdo());
        $commentaire = $managerCommentaire->find($idCommentaire);

        if (!$commentaire || $commentaire->getIdUtilisateur() !== $utilisateur->getIdUtilisateur()) {
            $this->afficherErreur("Vous ne pouvez modifier que vos propres commentaires.");
        }        

        if ($managerCommentaire->modifier($idCommentaire, $nouveauContenu)) {
            $this->redirectAvecSucces($commentaire->getIdTMDB(),$type);
        } else {
            $this->redirectAvecErreur($commentaire->getIdTMDB(),$type);
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
    private function redirectAvecErreur(?int $idTMDB, ?string $type): void
    {
        if ($type == "TV") {
            header("Location: index.php?controleur=oa&methode=afficherSerie&idOa=$idTMDB&erreur=1");
        } else{

        header("Location: index.php?controleur=oa&methode=afficherFilm&idOa=$idTMDB&erreur=1");
        }
        exit();
    }

    /**
     * @brief Redirige avec un message de succès
     * @param int|null $idTMDB Identifiant du film
     */
    private function redirectAvecSucces(?int $idTMDB, ?string $type): void
    {
        if ($type == "TV") {
            header("Location: index.php?controleur=oa&methode=afficherSerie&idOa=$idTMDB&succes=1");
        } else {
            header("Location: index.php?controleur=oa&methode=afficherFilm&idOa=$idTMDB&succes=1");
        }
        
        exit();
    }

    /**
     * @brief Affiche une page d'erreur
     * @param string $message Message d'erreur à afficher
     */
    private function afficherErreur(string $message): void
    {
        $erreurController = new ErreurController($this->getTwig(), $this->getLoader());
        $erreurController->renderErreur($message);
        exit();
    }
}

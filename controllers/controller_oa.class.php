<?php

/**
 * @file controller_oa.class.php
 * @author Thibault CHIPY, VINET LATRILLE Jules
 * @brief Contrôleur pour la gestion des œuvres audiovisuelles
 * @details Ce contrôleur gère l'affichage des œuvres et leurs détails.
 * @version 2.0
 * @date 2024-12-22
 */

require_once 'modeles/oa.dao.php';
require_once 'modeles/commentaire.dao.php';

class ControllerOA extends Controller
{
    private OADao $managerOa;
    private CommentaireDAO $managerCommentaire;

    /**
     * @brief Constructeur du contrôleur OA
     * @param \Twig\Environment $twig Environnement Twig
     * @param \Twig\Loader\FilesystemLoader $loader Loader Twig
     */
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
        $this->managerOa = new OADao();
        $this->managerCommentaire = new CommentaireDAO($this->getPdo());
    }

    /**
     * @brief Affiche les 10 films les mieux notés
     * @return void
     */
    public function listerFilms(): void
    {
        try {
            $oaListe = $this->managerOa->findMeilleurNote();
            $template = $this->getTwig()->load('index.html.twig');
            echo $template->render(['oaListe' => $oaListe]);
        } catch (Exception $e) {
            error_log('Erreur lors du listing des films : ' . $e->getMessage());
            $this->afficherErreur('Impossible d\'afficher la liste des films.');
        }
    }


    /**
     * @brief Affiche les détails d'un film spécifique
     * @return void
     */
    public function afficherFilm(): void
    {
        $idOa = $_GET['idOa'] ?? null;

        if (!$this->validerId($idOa)) {
            $this->afficherErreur('ID du film invalide ou non spécifié.');
            return;
        }

        try {
            $idOa = (int)$idOa;
            $oa = $this->managerOa->find($idOa);

            if (!$oa) {
                $this->afficherErreur('Film non trouvé.');
                return;
            }

            // Récupérer les commentaires du film
            $commentaires = $this->managerCommentaire->findByTMDB($oa->getIdOa());
            error_log("Nombre de commentaires : " . count($commentaires));

            // Récupérer les participants du film
            $participants = $this->managerOa->getParticipantsByFilmId($oa->getIdOa());
            error_log("Nombre de participants : " . count($participants));

            // Affichage dans la vue
            $template = $this->getTwig()->load('film.html.twig');
            echo $template->render([
                'oa' => $oa,
                'commentaires' => $commentaires,
                'participants' => $participants
            ]);
        } catch (Exception $e) {
            error_log('Erreur lors de l\'affichage du film : ' . $e->getMessage());
            $this->afficherErreur('Impossible d\'afficher les détails du film.');
        }
    }

    /**
     * @brief Valide un identifiant
     * @param mixed $id Identifiant à valider
     * @return bool
     */
    private function validerId($id): bool
    {
        return is_numeric($id) && (int)$id > 0;
    }

    /**
     * @brief Affiche une erreur à l'utilisateur
     * @param string $message Message d'erreur
     * @return void
     */
    private function afficherErreur(string $message): void
    {
        $template = $this->getTwig()->load('erreur.html.twig');
        echo $template->render(['message' => $message]);
        exit();
    }
}

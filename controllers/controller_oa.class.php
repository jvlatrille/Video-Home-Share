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
            $oaRandomListe = $this->managerOa->findRandomOeuvres();
            $template = $this->getTwig()->load('index.html.twig');
            echo $template->render([
                'oaListe' => $oaListe,
                'oaRandomListe' => $oaRandomListe
            ]);
        } catch (Exception $e) {
            error_log('Erreur lors du listing des films : ' . $e->getMessage());
            die('Impossible d\'afficher la liste des films.');
        }
    }



    /**
     * @brief Affiche les détails d'un film spécifique
     * @return void
     */
    /**
     * @brief Affiche les détails d'un film spécifique
     * @return void
     */
    public function afficherFilm(): void
    {
        $idOa = $_GET['idOa'] ?? null;

        if (!$this->validerId($idOa)) {
            die('ID du film invalide ou non spécifié.');
        }

        try {
            $idOa = (int)$idOa;
            $oa = $this->managerOa->find($idOa);

            if (!$oa) {
                die('Film non trouvé.');
            }

            // Récupérer les commentaires du film
            $commentaires = $this->managerCommentaire->findByTMDB($oa->getIdOa());
            error_log("Nombre de commentaires : " . count($commentaires));

            // Récupérer les participants du film
            $participants = $this->managerOa->getParticipantsByFilmId($oa->getIdOa());
            error_log("Nombre de participants : " . count($participants));

            $utilisateurNote = null;
            $watchListListe = null;

            // Récupérer les données spécifiques à l'utilisateur s'il est connecté
            if (isset($_SESSION['utilisateur'])) {
                $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
                $managerWatchList = new WatchListDao($this->getPdo());

                // Récupération des watchlists de l'utilisateur
                $watchListListe = $managerWatchList->findAll($utilisateurConnecte->getIdUtilisateur());

                // Récupération de la note de l'utilisateur pour ce film
                $utilisateurNote = $this->managerOa->getNoteUtilisateur($utilisateurConnecte->getIdUtilisateur(), $oa->getIdOa());
            }

            // Affichage avec Twig
            $template = $this->getTwig()->load('film.html.twig');
            echo $template->render([
                'oa' => $oa,
                'commentaires' => $commentaires,
                'participants' => $participants,
                'watchListListe' => $watchListListe,
                'utilisateurNote' => $utilisateurNote, // Transmettre la note utilisateur à la vue
            ]);
        } catch (Exception $e) {
            error_log('Erreur lors de l\'affichage du film : ' . $e->getMessage());
            die('Impossible d\'afficher les détails du film.');
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
     * @brief Récupère des œuvres aléatoires
     * @return void
     */
    public function decouvrirPlus(): void
    {
        try {
            $oaListe = $this->managerOa->findRandomOeuvres();
            header('Content-Type: application/json');
            echo json_encode($oaListe);
            exit;
        } catch (Exception $e) {
            error_log('Erreur lors de la récupération des œuvres aléatoires : ' . $e->getMessage());
            echo json_encode(['error' => 'Impossible de charger des œuvres aléatoires']);
            exit;
        }
    }


    public function noterFilm(): void
    {
        if (!isset($_SESSION['utilisateur'])) {
            die(json_encode(['success' => false, 'message' => 'Utilisateur non connecté.']));
        }

        $idUtilisateur = unserialize($_SESSION['utilisateur'])->getIdUtilisateur();
        $input = json_decode(file_get_contents('php://input'), true);

        $idTMDB = $input['idFilm'] ?? null;
        $note = $input['note'] ?? null;

        if (!$idTMDB || !$note) {
            die(json_encode(['success' => false, 'message' => 'Données invalides reçues : ' . json_encode($input)]));
        }

        try {
            $result = $this->managerOa->ajouterNote((int)$idUtilisateur, (int)$idTMDB, (int)$note);
            echo json_encode(['success' => $result]);
        } catch (Exception $e) {
            die(json_encode(['success' => false, 'message' => $e->getMessage()]));
        }
    }


    public function afficherMoyenneNotes(): void
    {
        $idTMDB = $_GET['idTMDB'] ?? null;
        $noteTMDB = $_GET['noteTMDB'] ?? null;

        if (!$idTMDB || !$noteTMDB) {
            die(json_encode(['success' => false, 'message' => 'Données invalides.']));
        }

        try {
            $moyenne = $this->managerOa->calculerMoyenneNotes((int)$idTMDB, (float)$noteTMDB);
            echo json_encode(['success' => true, 'moyenne' => $moyenne]);
        } catch (Exception $e) {
            die(json_encode(['success' => false, 'message' => $e->getMessage()]));
        }
    }

    public function afficherNoteUtilisateur(): void
    {
        if (!isset($_SESSION['utilisateur'])) {
            die(json_encode(['success' => false, 'message' => 'Utilisateur non connecté.']));
        }

        $idUtilisateur = unserialize($_SESSION['utilisateur'])->getIdUtilisateur();
        $idOa = $_GET['idOa'] ?? null;

        if (!$this->validerId($idOa)) {
            die(json_encode(['success' => false, 'message' => 'ID du film invalide ou non spécifié.']));
        }

        try {
            $idOa = (int)$idOa;
            $note = $this->managerOa->getNoteUtilisateur($idUtilisateur, $idOa);
            echo json_encode(['success' => true, 'note' => $note]);
        } catch (Exception $e) {
            die(json_encode(['success' => false, 'message' => $e->getMessage()]));
        }
    }
}

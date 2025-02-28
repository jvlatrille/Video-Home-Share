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
     * @brief Affiche les détails d'un film spécifique
     * @return void
     */
    public function afficherFilm(): void
    {
        $idOa = $_GET['idOa'] ?? null;

        if (!$this->validerId($idOa)) {
            $this->afficherErreur("ID du film invalide ou non spécifié.");
        }        

        try {
            $idOa = (int)$idOa;
            $oa = $this->managerOa->find($idOa);

            if (!$oa) {
                $this->afficherErreur("Film non trouvé.");
            }            

            // Récupérer les commentaires du film
            $commentaires = $this->managerCommentaire->findByTMDB($oa->getIdOa(),$oa->getType());
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
                $watchListListe = $managerWatchList->findAll($utilisateurConnecte->getIdUtilisateur());
                $utilisateurNote = $this->managerOa->getNoteUtilisateur($utilisateurConnecte->getIdUtilisateur(), $oa->getIdOa());
            }

            // Récupérer les suggestions de films
            $suggestions = $this->managerOa->findSuggestions($oa->getIdOa());

            // Récupérer les fond d'écran
            $backdrops = $this->managerOa->getBackdrops($oa->getIdOa(), 'movie');

            // Affichage avec Twig
            $template = $this->getTwig()->load('film.html.twig');
            echo $template->render([
                'oa' => $oa,
                'commentaires' => $commentaires,
                'participants' => $participants,
                'watchListListe' => $watchListListe,
                'utilisateurNote' => $utilisateurNote,
                'suggestions' => $suggestions,
                'backdrops' => $backdrops
            ]);
        } catch (Exception $e) {
            $this->afficherErreur("Impossible d'afficher les détails du film.");
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
            $oaListe = array_merge($oaListe, $this->managerOa->findRandomSeries());
            shuffle($oaListe);
            array_splice($oaListe, 10);
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
            echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté.']);
            exit();
        }

        $idUtilisateur = unserialize($_SESSION['utilisateur'])->getIdUtilisateur();
        $input = json_decode(file_get_contents('php://input'), true);

        $idTMDB = $input['idFilm'] ?? null;
        $note = $input['note'] ?? null;

        if (!$idTMDB || !$note) {
            echo json_encode(['success' => false, 'message' => 'Données invalides reçues : ' . json_encode($input)]);
            exit();
        }        

        try {
            $result = $this->managerOa->ajouterNote((int)$idUtilisateur, (int)$idTMDB, (int)$note);
            echo json_encode(['success' => $result]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit();
        }
    }


    public function afficherMoyenneNotes(): void
    {
        $idTMDB = $_GET['idTMDB'] ?? null;
        $noteTMDB = $_GET['noteTMDB'] ?? null;

        if (!$idTMDB || !$noteTMDB) {
            echo json_encode(['success' => false, 'message' => 'Données invalides.']);
            exit();
        }        

        try {
            $moyenne = $this->managerOa->calculerMoyenneNotes((int)$idTMDB, (float)$noteTMDB);
            echo json_encode(['success' => true, 'moyenne' => $moyenne]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit();
        }
    }

    public function afficherNoteUtilisateur(): void
    {
        if (!isset($_SESSION['utilisateur'])) {
            echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté.']);
            exit();
        }        

        $idUtilisateur = unserialize($_SESSION['utilisateur'])->getIdUtilisateur();
        $idOa = $_GET['idOa'] ?? null;

        if (!$this->validerId($idOa)) {
            echo json_encode(['success' => false, 'message' => 'ID du film invalide ou non spécifié.']);
            exit();
        }        

        try {
            $idOa = (int)$idOa;
            $note = $this->managerOa->getNoteUtilisateur($idUtilisateur, $idOa);
            echo json_encode(['success' => true, 'note' => $note]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit();
        }
    }


    /**
     * @brief Affiche les 10 séries les mieux notées
     * @return void
     */
    public function listerSeries(): void
    {
        try {
            $oaListe = $this->managerOa->findMeilleurNoteSerie();
            $template = $this->getTwig()->load('series.html.twig');
            echo $template->render([
                'oaListe' => $oaListe
            ]);
        } catch (Exception $e) {
            error_log('Erreur lors du listing des séries : ' . $e->getMessage());
            $this->afficherErreur("Impossible d'afficher la liste des séries.");
        }
    }

    /**
     * @brief Affiche les détails d'une série spécifique
     * @return void
     */
    public function afficherSerie(): void
    {
        $idOa = $_GET['idOa'] ?? null;

        if (!$this->validerId($idOa)) {
            $this->afficherErreur("ID de la série invalide ou non spécifié.");
        }        

        try {
            $idOa = (int)$idOa;
            $oa = $this->managerOa->findSerie($idOa);

            if (!$oa) {
                $this->afficherErreur("Série non trouvée.");
            }            

            // Récupérer les commentaires de la série
            $commentaires = $this->managerCommentaire->findByTMDB($oa->getIdOa(),$oa->getType());
            error_log("Nombre de commentaires : " . count($commentaires));
            // Récupérer les participants de la série
            $participants = $this->managerOa->getParticipantsBySerieId($oa->getIdOa());
            error_log("Nombre de participants : " . count($participants));

            // Récupérer les suggestions de séries
            $suggestions = $this->managerOa->findSuggestionsSerie($oa->getIdOa());

            $backdrops = $this->managerOa->getBackdrops($oa->getIdOa(), 'tv');

            //Recuperer les watchlist de l'utilisateur
            if (isset($_SESSION['utilisateur'])) {
                $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
                $managerWatchList = new WatchListDao($this->getPdo());
                $watchListListe = $managerWatchList->findAll($utilisateurConnecte->getIdUtilisateur());
                $template = $this->getTwig()->load('serie.html.twig');
                echo $template->render([
                    'watchListListe' => $watchListListe,
                    'oa' => $oa,
                    'commentaires' => $commentaires,
                    'participants' => $participants,
                    'suggestions' => $suggestions,
                    'backdrops' => $backdrops,
                ]);
                return;
            }

            // Affichage dans la vue normale si l'utilisateur n'est pas connecté
            $template = $this->getTwig()->load('serie.html.twig');
            echo $template->render([
                'oa' => $oa,
                'commentaires' => $commentaires,
                'participants' => $participants,
                'suggestions' => $suggestions,
                'backdrops' => $backdrops,
            ]);
        } catch (Exception $e) {
            error_log('Erreur lors de l\'affichage de la série : ' . $e->getMessage());
            $this->afficherErreur("Impossible d'afficher les détails de la série.");
        }
    }

    /**
     * @brief Affiche 10 suggestions basées sur un film donné
     * @return void
     */
    public function suggestionsFilm(): void
    {
        $idFilm = $_GET['idFilm'] ?? null;

        if (!$this->validerId($idFilm)) {
            echo json_encode(['success' => false, 'message' => 'ID du film invalide ou non spécifié.']);
            exit();
        }        

        try {
            $suggestions = $this->managerOa->findSuggestions((int)$idFilm);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'suggestions' => $suggestions]);
            exit;
        } catch (Exception $e) {
            error_log('Erreur lors de la récupération des suggestions : ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Impossible de récupérer les suggestions.']);
            exit;
        }
    }

    /**
     * @brief Recupere les genres 
     * @return void
     */
    public function getGenres(): void
    {
        try {
            $genres = $this->managerOa->getGenresFilms();
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'genres' => $genres]);
            exit;
        } catch (Exception $e) {
            error_log('Erreur lors de la récupération des genres : ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Impossible de récupérer les genres.']);
            exit;
        }
    }

    /**
     * @brief Recupere les films en fonction des genres
     * @return void
     */

     public function getSuggestionsByGenre():void {
        $genre = $_GET['genre'] ?? null;

        if (!$genre) {
            echo json_encode(['success' => false, 'message' => 'Genre invalide ou non spécifié.']);
            exit();
        }        

        try {
            $suggestions = $this->managerOa->findSuggestionsByGenre($genre);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'suggestions' => $suggestions]);
            exit;
        } catch (Exception $e) {
            error_log('Erreur lors de la récupération des suggestions : ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Impossible de récupérer les suggestions.']);
            exit;
        }
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

<?php

/**
 * @file oa.dao.php
 * @author Thibault CHIPY, VINET LATRILLE Jules
 * @brief Classe OADao pour gérer les accès aux données des œuvres audiovisuelles
 * @details Cette classe combine les appels API TMDB et les accès à la base de données pour les œuvres audiovisuelles.
 * @version 2.0
 * @date 2024-12-22
 */

require_once 'oa.class.php';

class OADao
{
    /** @brief URL de base de l'API TMDB */
    private string $apiBaseUrl = 'https://api.themoviedb.org/3';

    /** @brief Clé API TMDB */
    private string $apiKey = TMDB_CLE_KEY;

    /** @brief Token d'accès API TMDB */
    private string $accessToken = TMDB_TOKEN_ACCES;

    /**
     * @brief Établit une connexion PDO avec la base de données
     * @return PDO Instance PDO
     */
    private function getConnection(): PDO
    {
        static $pdo = null;

        if ($pdo === null) {
            try {
                $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                error_log('Erreur PDO : ' . $e->getMessage());
                throw $e;
            }
        }

        return $pdo;
    }

    /**
     * @brief Effectue une requête API TMDB
     * @param string $endpoint L'URL de l'endpoint API
     * @param array $params Paramètres supplémentaires
     * @return array Réponse API sous forme de tableau associatif
     */
    private function makeApiRequest(string $endpoint, array $params = [], bool $useAccessToken = false): array
    {
        $url = $this->apiBaseUrl . $endpoint;

        if ($useAccessToken) {
            // Pour les requêtes sécurisées avec Access Token
            $headers = [
                'Authorization: Bearer ' . $this->accessToken,
                'Content-Type: application/json',
            ];
        } else {
            // Pour les requêtes publiques avec API Key
            $params['api_key'] = $this->apiKey;
            $headers = ['Content-Type: application/json'];
        }

        // Ajouter les paramètres à l'URL même avec AccessToken
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        // C'est pas bien mais on le fait TEMPORAIREMENT pour éviter les erreurs de certificat
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            die('Erreur cURL : ' . curl_error($curl));
        }

        curl_close($curl);

        $decodedResponse = json_decode($response, true);
        if (isset($decodedResponse['status_code'])) {
            die('Erreur API TMDB : ' . $decodedResponse['status_message']);
        }

        return $decodedResponse ?? [];
    }



    /**
     * @brief Retourne l'URL complète du poster
     * @param string|null $posterPath Chemin de l'image
     * @param string $size Taille de l'image
     * @return string URL complète du poster
     */
    private function getPosterUrl(?string $posterPath, string $size = 'original'): string
    {
        $baseUrl = 'https://image.tmdb.org/t/p/';
        $defaultImage = 'https://via.placeholder.com/500x750?text=Image+non+disponible';

        return $posterPath ? $baseUrl . $size . $posterPath : $defaultImage;
    }

    /**
     * @brief Récupère tous les backdrops d'un film en optimisant la qualité d'affichage
     * @param int $idOa Identifiant TMDB du film
     * @param string $type Type de l'œuvre (movie ou tv)
     * @return array Liste des URLs des backdrops avec version réduite et HD
     */
    public function getBackdrops(int $idOa, string $type): array
    {
        $response = $this->makeApiRequest("/$type/$idOa/images", [], true);
        if (!isset($response['backdrops']) || empty($response['backdrops'])) {
            return [
                [
                    'small' => 'https://via.placeholder.com/300x169?text=Image+non+disponible',
                    'full' => 'https://via.placeholder.com/1280x720?text=Image+non+disponible'
                ]
            ];
        }

        return array_map(fn($img) => [
            'small' => 'https://image.tmdb.org/t/p/w300' . $img['file_path'],
            'full' => 'https://image.tmdb.org/t/p/original' . $img['file_path']
        ], array_slice($response['backdrops'], 0, 10)); // Limite à 10 images max
    }

    


    /**
     * @brief Analyse les participants à partir des crédits API
     * @param array $credits Données des crédits API
     * @return array Liste des participants
     */
    private function parseParticipants(array $credits): array
    {
        $participants = [];
        $baseImageUrl = 'https://image.tmdb.org/t/p/w185';

        foreach (['cast', 'crew'] as $key) {
            foreach ($credits[$key] ?? [] as $member) {
                $participants[] = [
                    'nom' => $member['name'] ?? 'Inconnu',
                    'role' => $member['character'] ?? $member['job'] ?? 'Non spécifié',
                    'photo' => isset($member['profile_path']) ? $baseImageUrl . $member['profile_path'] : null,
                ];
            }
        }

        return $participants;
    }

    /**
     * @brief Récupère le nom du producteur d'une œuvre
     * @param array $crew Liste des membres de l'équipe (crew) du film
     * @return string|null Nom du producteur ou null si non trouvé
     */
    private function getProducer(array $crew): ?string
    {
        foreach ($crew as $member) {
            if (isset($member['job']) && stripos($member['job'], 'producer') !== false) {
                return $member['name'] ?? null; // Retourne le premier producteur trouvé
            }
        }
        return null; // Retourne null si aucun producteur trouvé
    }

    /**
     * @brief Récupère le créateur d'une série
     * @param array $createdBy Liste des créateurs de la série
     * @return string|null Nom du créateur ou null si non trouvé
     */
    private function getCreator(array $createdBy): ?string
    {
        return $createdBy[0]['name'] ?? null;
    }

    /**
     * @brief Hydrate un objet OA avec des données
     * @param array $data Données API
     * @return OA|null
     */
    private function hydrate(array $data): ?OA
    {
        return new OA(
            $data['id'] ?? null,
            $data['title'] ?? 'Titre inconnu',
            $data['vote_average'] ?? 0.0,
            'Film',
            $data['overview'] ?? 'Description non disponible',
            $data['release_date'] ?? 'Date inconnue',
            $data['original_language'] ?? 'Langue inconnue',
            $data['runtime'] ?? null,
            isset($data['genres']) ? array_column($data['genres'], 'name') : [],
            null,
            $this->getPosterUrl(posterPath: $data['poster_path'] ?? null),
            $this->getBackdrops($data['id'] ?? null, 'movie'),
            $this->parseParticipants($data['credits'] ?? []),
            $data['producer'] ?? null,
            null,
            null
        );
    }

    /**
     * @brief Hydrate une liste d'objets OA avec des données
     * @param array $dataList Liste de données API
     * @return array Liste d'objets OA
     */
    private function hydrateAll(array $dataList): array
    {
        $oaList = [];
        foreach ($dataList as $data) {
            $oaList[] = $this->hydrate($data);
        }
        return $oaList;
    }

    /**
     * @brief Récupère les détails d'un film par son ID
     * @param int|null $id Identifiant du film
     * @return OA|null Objet OA hydraté
     */
    public function find(?int $id): ?OA
    {
        $movie = $this->makeApiRequest("/movie/$id", ['language' => 'fr-FR'], true);
        $credits = $this->makeApiRequest("/movie/$id/credits", [], true);
        $movie['producer'] = $this->getProducer($credits['crew'] ?? []);
        $movie['participants'] = $this->parseParticipants($credits);
        return $this->hydrate($movie);
    }



    /**
     * @brief Récupère les 10 films les mieux notés
     * @return array Liste des objets OA
     */
    public function findMeilleurNote(): array
    {
        $results = $this->makeApiRequest('/movie/top_rated', ['language' => 'fr-FR', 'page' => 1]);
        if (!isset($results['results']) || empty($results['results'])) {
            error_log('Aucun film trouvé dans les mieux notés.');
            return [];
        }
        return $this->hydrateAll($results['results']);
    }


    /**
     * @brief Récupère les participants d'un film via son ID TMDB
     * @param int $idTMDB Identifiant TMDB du film
     * @return array Liste des participants (nom, rôle, photo)
     */
    public function getParticipantsByFilmId(int $idTMDB): array
    {
        $credits = $this->makeApiRequest("/movie/$idTMDB/credits", ['language' => 'fr-FR'], true);

        if (empty($credits)) {
            error_log("Aucun crédit trouvé pour le film ID : $idTMDB");
            return [];
        }

        return $this->parseParticipants($credits);
    }

    /**
     * @brief Récupère les participants d'une série via son ID TMDB
     * @param int $idTMDB Identifiant TMDB de la série
     * @return array Liste des participants (nom, rôle, photo)
     */
    public function getParticipantsBySerieId(int $idTMDB): array
    {
        $credits = $this->makeApiRequest("/tv/$idTMDB/credits", ['language' => 'fr-FR'], true);

        if (empty($credits)) {
            error_log("Aucun crédit trouvé pour la série ID : $idTMDB");
            return [];
        }

        return $this->parseParticipants($credits);
    }

    /**
     * @brief Récupère des œuvres aléatoires depuis l'API TMDB
     * @return array Liste d'objets OA
     */
    public function findRandomOeuvres(): array
    {
        $randomPage = rand(1, 100);
        $results = $this->makeApiRequest('/movie/popular', ['include_adult' => false, 'language' => 'fr-FR', 'page' => $randomPage]);

        if (!isset($results['results']) || empty($results['results'])) {
            error_log('Aucune œuvre aléatoire trouvée.');
            return [];
        }

        return array_map(function ($data) {
            return [
                'idOa' => $data['id'] ?? null,
                'nom' => $data['title'] ?? 'Titre inconnu',
                'posterPath' => $this->getPosterUrl($data['poster_path'] ?? null),
                'type' => 'Film',
            ];
        }, array_slice($results['results'], 0, 10));
    }

    /**
     * @brief Récupère des séries aléatoires depuis l'API TMDB
     * @return array Liste d'objets OA
     */
    public function findRandomSeries(): array
    {
        $randomPage = rand(1, 100);
        $results = $this->makeApiRequest('/tv/popular', ['include_adult' => false, 'language' => 'fr-FR', 'page' => $randomPage]);

        if (!isset($results['results']) || empty($results['results'])) {
            error_log('Aucune série aléatoire trouvée.');
            return [];
        }

        return array_map(function ($data) {
            return [
                'idOa' => $data['id'] ?? null,
                'nom' => $data['name'] ?? 'Titre inconnu',
                'posterPath' => $this->getPosterUrl($data['poster_path'] ?? null),
                'type' => 'TV',
            ];
        }, array_slice($results['results'], 0, 10));
    }

    /**
     * @brief Recherche des films par titre
     * @param string $query Requête de recherche
     * @return array Liste des objets OA
     * 
     */
    public function rechercheFilmParNom(string $query): array
    {
        $results = $this->makeApiRequest('/search/multi', ['include_adult' => false, 'query' => $query, 'language' => 'fr-FR']);
        if (!isset($results['results']) || empty($results['results'])) {
            error_log('Aucun résultat trouvé pour la recherche : ' . $query);
            return [];
        }

        $hydratedResults = [];
        foreach ($results['results'] as $result) {
            if ($result['media_type'] == 'tv') {
                $hydratedResults[] = $this->hydrateSerie($result);
            } else {
                $hydratedResults[] = $this->hydrate($result);
            }
        }
        return $hydratedResults;
    }

    //Implementation des séries

    /**
     * @brief Hydrate un objet OA avec des données pour une série
     * @param array $data Données API
     * @return OA|null
     */
    private function hydrateSerie(array $data): ?OA
    {
        return new OA(
            $data['id'] ?? null,
            $data['name'] ?? 'Titre inconnu',
            $data['vote_average'] ?? 0.0,
            'TV',
            $data['overview'] ?? 'Description non disponible',
            $data['first_air_date'] ?? 'Date inconnue',
            $data['original_language'] ?? 'Langue inconnue',
            $data['episode_run_time'][0] ?? null,
            isset($data['genres']) ? array_column($data['genres'], 'name') : [],
            null,
            $this->getPosterUrl($data['poster_path'] ?? null),
            $backdrops = $this->getBackdrops($data['id'] ?? null, type: 'tv'),
            $this->parseParticipants($data['credits'] ?? []),
            $data['producteur'] = $this->getCreator($data['created_by'] ?? []),
            $data['number_of_seasons'] ?? null,
            $data['number_of_episodes'] ?? null
        );
    }

    /**
     * @brief Hydrate une liste d'objets OA pour les séries avec des données spécifiques
     * @param array $dataList Liste de données API
     * @return array Liste d'objets OA
     */
    private function hydrateAllSerie(array $dataList): array
    {
        $oaList = [];
        foreach ($dataList as $data) {
            $oaList[] = $this->hydrateSerie($data);
        }
        return $oaList;
    }

    /**
     * @brief Récupère les 10 séries les mieux notées
     * @return array Liste des objets OA
     */
    public function findMeilleurNoteSerie(): array
    {
        $results = $this->makeApiRequest('/tv/top_rated', ['include_adult' => false, 'language' => 'fr-FR', 'page' => 1]);
        if (!isset($results['results']) || empty($results['results'])) {
            error_log('Aucune série trouvée dans les mieux notées.');
            return [];
        }
        return $this->hydrateAllSerie($results['results']);
    }



    /**
     * @brief Récupère les détails d'une série par son ID
     * @param int|null $id Identifiant de la série
     * @return OA|null Objet OA hydraté
     */
    public function findSerie(?int $id): ?OA
    {
        $serie = $this->makeApiRequest("/tv/$id", ['language' => 'fr-FR'], true);
        $credits = $this->makeApiRequest("/tv/$id/credits", [], true);
        $serie['participants'] = $this->parseParticipants($credits);
        $serie['producer'] = $this->getProducer($credits['crew'] ?? []);
        return $this->hydrateSerie($serie);
    }

    /**
     * @brief Récupère toutes les notes pour un film
     * @param int $idTMDB Identifiant TMDB du film
     * @return array Liste des notes
     */
    public function recupererNotesParFilm(int $idTMDB): array
    {
        $pdo = $this->getConnection();
        $query = 'SELECT ' . PREFIXE_TABLE . 'notes FROM notes WHERE idTMDB = :idTMDB';
        $stmt = $pdo->prepare($query);
        $stmt->execute(['idTMDB' => $idTMDB]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }


    /**
     * @brief Ajoute une note pour un utilisateur sur une œuvre
     * @param int $idUtilisateur Identifiant de l'utilisateur
     * @param int $idTMDB Identifiant TMDB du film
     * @param int $note Note attribuée (entre 1 et 5)
     * @return bool Retourne true si l'opération est réussie
     */
    public function ajouterNote(int $idUtilisateur, int $idTMDB, int $note): bool
    {
        if ($note < 1 || $note > 5) {
            die('La note doit être comprise entre 1 et 5.');
        }

        $pdo = $this->getConnection();
        $query = 'INSERT INTO ' . PREFIXE_TABLE . 'notes (idUtilisateur, idTMDB, note) 
              VALUES (:idUtilisateur, :idTMDB, :note)
              ON DUPLICATE KEY UPDATE note = :note';
        $stmt = $pdo->prepare($query);
        $result = $stmt->execute([
            'idUtilisateur' => $idUtilisateur,
            'idTMDB' => $idTMDB,
            'note' => $note
        ]);

        if (!$result) {
            error_log('Erreur lors de l\'insertion de la note : ' . print_r($stmt->errorInfo(), true));
        }

        return $result;
    }


    /**
     * @brief Calcule la moyenne des notes pour un film
     * @param int $idTMDB Identifiant TMDB du film
     * @param float $noteTMDB Note TMDB
     * @return float Moyenne des notes
     */
    public function calculerMoyenneNotes(int $idTMDB, float $noteTMDB): float
    {
        $notes = $this->recupererNotesParFilm($idTMDB);
        if (empty($notes)) {
            return $noteTMDB; // Pas de notes utilisateur, retourne la note TMDB
        }

        $moyenneUtilisateurs = array_sum($notes) / count($notes);
        return ($moyenneUtilisateurs + $noteTMDB) / 2; // Moyenne pondérée
    }

    /**
     * @brief Récupère la note d'un utilisateur pour un film donné
     * @param int $idUtilisateur Identifiant de l'utilisateur
     * @param int $idTMDB Identifiant TMDB du film
     * @return int|null Note de l'utilisateur ou null si non noté
     */
    public function getNoteUtilisateur(int $idUtilisateur, int $idTMDB): ?int
    {
        $pdo = $this->getConnection();
        $query = 'SELECT note FROM ' . PREFIXE_TABLE . 'notes WHERE idUtilisateur = :idUtilisateur AND idTMDB = :idTMDB';
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'idUtilisateur' => $idUtilisateur,
            'idTMDB' => $idTMDB
        ]);
        $note = $stmt->fetchColumn();
        return $note !== false ? (int)$note : null;
    }

    /**
     * @brief Récupère 10 suggestions basées sur un film donné
     * @param int $idFilm Identifiant TMDB du film
     * @return array Liste des suggestions sous forme d'objets OA
     */
    public function findSuggestions(int $idFilm): array
    {
        $results = $this->makeApiRequest("/movie/$idFilm/recommendations", ['language' => 'fr-FR', 'page' => 1], true);

        if (!isset($results['results']) || empty($results['results'])) {
            error_log('Aucune suggestion trouvée pour le film ID : ' . $idFilm);
            return [];
        }

        return $this->hydrateAll(array_slice($results['results'], 0, 10));
    }

    /**
     * @brief Récupère des suggestions basées sur une série donnée
     * @param int $idTMDB Identifiant TMDb de la série
     * @return array Liste d'objets OA suggérés
     */
    public function findSuggestionsSerie(int $idTMDB): array
    {
        $results = $this->makeApiRequest("/tv/$idTMDB/recommendations", ['language' => 'fr-FR']);
        if (!isset($results['results']) || empty($results['results'])) {
            error_log("Aucune suggestion trouvée pour la série ID : $idTMDB");
            return [];
        }
        return $this->hydrateAllSerie($results['results']);
    }

    /**
     * @brief Récupère les genres 
     * @return array Liste des genres
     */
    public function getGenresFilms(): array{
        $resultsFilms = $this->makeApiRequest('/genre/movie/list', ['language' => 'fr-FR']);
        if (!isset($resultsFilms['genres']) || empty($resultsFilms['genres'])) {
            error_log('Aucun genre trouvé pour les films.');
            return [];
        }
        
        return $resultsFilms['genres'];
    }

    /**
     * @brief Récupère des suggestions basées sur un genre donné
     * @param int $idGenre Identifiant du genre
     * @return array Liste des suggestions sous forme d'objets OA
     */
    public function findSuggestionsByGenre(int $idGenre): array
    {
        $results = $this->makeApiRequest('/discover/movie', ['include_adult'=>false,'with_genres' => $idGenre, 'language' => 'fr-FR', 'page' => 1], true);

        if (!isset($results['results']) || empty($results['results'])) {
            error_log('Aucune suggestion trouvée pour le genre ID : ' . $idGenre);
            return [];
        }
        return array_slice($results['results'], 0, 10);
    }
}

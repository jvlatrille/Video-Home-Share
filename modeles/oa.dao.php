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
            if (
                isset($member['job']) &&
                in_array($member['job'], ['Producer', 'Executive Producer', 'producteur'])
            ) {
                return $member['name'] ?? 'Inconnu';
            }
        }

        error_log("Aucun producteur trouvé dans l'équipe : " . print_r($crew, true));
        return 'Non spécifié';
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
            $this->getPosterUrl($data['poster_path'] ?? null),
            $this->parseParticipants($data['credits'] ?? []),
            $this->getProducer($data['credits']['crew'] ?? [])
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
        $movie['participants'] = $this->parseParticipants($credits);
        $movie['producer'] = $this->getProducer($credits['crew'] ?? []);

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
     * @brief Récupère des œuvres aléatoires depuis l'API TMDB
     * @return array Liste d'objets OA
     */
    public function findRandomOeuvres(): array
    {
        $randomPage = rand(1, 100); // Choisit une page aléatoire
        $results = $this->makeApiRequest('/movie/popular', ['language' => 'fr-FR', 'page' => $randomPage]);

        if (!isset($results['results']) || empty($results['results'])) {
            error_log('Aucune œuvre aléatoire trouvée.');
            return [];
        }

        // On sélectionne aléatoirement 10 œuvres de la page récupérée
        $randomOeuvres = array_slice($results['results'], 0, 10);
        return $this->hydrateAll($randomOeuvres);
    }
}

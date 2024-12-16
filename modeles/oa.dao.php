<?php

require_once 'oa.class.php';

class OADao
{
    private $apiBaseUrl = 'https://api.themoviedb.org/3';
    private $apiKey = TMDB_CLE_KEY;
    private $accessToken = TMDB_TOKEN_ACCES;

    private function makeApiRequest(string $endpoint, array $params = []): array
    {
        $url = $this->apiBaseUrl . $endpoint . '?api_key=' . $this->apiKey;

        // Ajouter les paramètres supplémentaires
        $params['include_image_language'] = 'fr,null'; // Inclut les images sans langue spécifique
        foreach ($params as $key => $value) {
            $url .= "&$key=" . urlencode($value);
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->accessToken,
            'Content-Type: application/json',
        ]);

        $response = curl_exec($curl);

        // Vérification des erreurs cURL
        if (curl_errno($curl)) {
            error_log('Erreur cURL : ' . curl_error($curl));
            curl_close($curl);
            return [];
        }

        curl_close($curl);

        $decodedResponse = json_decode($response, true);

        // Vérification des erreurs API TMDB
        if (isset($decodedResponse['status_code'])) {
            error_log('Erreur API TMDB : ' . $decodedResponse['status_message']);
            return [];
        }

        return $decodedResponse ?? [];
    }

    private function getPosterUrl(?string $posterPath, string $size = 'w500'): string
    {
        $baseUrl = 'https://image.tmdb.org/t/p/';
        $defaultImage = 'https://via.placeholder.com/500x750?text=Image+non+disponible';

        return $posterPath ? $baseUrl . $size . $posterPath : $defaultImage;
    }

    public function find(?int $id): ?OA
    {
        $movie = $this->makeApiRequest("/movie/$id", ['language' => 'fr-FR']);

        if (empty($movie)) {
            error_log("Aucun film trouvé pour l'ID : $id");
            return null;
        }

        var_dump($this->getPosterUrl($movie['poster_path']));

        return new OA(
            $movie['id'],
            $movie['title'],
            $movie['vote_average'],
            'Film',
            $movie['overview'],
            $movie['release_date'],
            $movie['original_language'],
            $movie['runtime'],
            array_column($movie['genres'], 'name'),
            null,
            $this->getPosterUrl($movie['poster_path']) // Passe l'URL complète ici
        );
    }

    public function findMeilleurNote(): array
    {
        $results = $this->makeApiRequest('/movie/top_rated', ['language' => 'fr-FR', 'page' => 1]);
        $movies = [];

        if (empty($results['results'])) {
            error_log("Aucun film trouvé dans les films les mieux notés.");
            return $movies;
        }

        foreach ($results['results'] as $movie) {
            $runtime = $movie['runtime'] ?? null; // Vérification de l'existence de 'runtime'
            $genres = isset($movie['genres']) && is_array($movie['genres']) ? array_column($movie['genres'], 'name') : null; // Vérification de 'genres'

            $movies[] = new OA(
                $movie['id'],
                $movie['title'],
                $movie['vote_average'],
                'Film',
                $movie['overview'],
                $movie['release_date'],
                $movie['original_language'],
                $runtime, // Utilisation de $runtime après vérification
                $genres,  // Utilisation de $genres après vérification
                null, // Pas de collection ici
                $this->getPosterUrl($movie['poster_path'])
            );
        }

        return $movies;
    }
}

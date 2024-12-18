<?php

require_once 'oa.class.php';

class OADao
{
    private $apiBaseUrl = 'https://api.themoviedb.org/3';
    private $apiKey = TMDB_CLE_KEY;
    private $accessToken = TMDB_TOKEN_ACCES;

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

    private function makeApiRequest(string $endpoint, array $params = []): array
    {
        $url = $this->apiBaseUrl . $endpoint . '?api_key=' . $this->apiKey;
        $params['include_image_language'] = 'fr,null';

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

        if (curl_errno($curl)) {
            error_log('Erreur cURL : ' . curl_error($curl));
            curl_close($curl);
            return [];
        }

        curl_close($curl);

        $decodedResponse = json_decode($response, true);
        if (isset($decodedResponse['status_code'])) {
            error_log('Erreur API TMDB : ' . $decodedResponse['status_message']);
            return [];
        }

        return $decodedResponse ?? [];
    }

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
            $data['producer'] ?? 'Non spécifié',
            $this->getPosterUrl($data['poster_path'] ?? null),
            $data['participants'] ?? []
        );
    }


    private function hydrateAll(array $dataList): array
    {
        return array_map(fn($data) => $this->hydrate($data), $dataList);
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

        $credits = $this->makeApiRequest("/movie/$id/credits");
        $movie['participants'] = $this->parseParticipants($credits);
        $movie['producer'] = $this->getProducer($credits['crew'] ?? []);

        return $this->hydrate($movie);
    }

    public function findMeilleurNote(): array
    {
        $results = $this->makeApiRequest('/movie/top_rated', ['language' => 'fr-FR', 'page' => 1]);
        if (empty($results['results'])) {
            error_log("Aucun film trouvé dans les films les mieux notés.");
            return [];
        }

        return $this->hydrateAll($results['results']);
    }

    private function parseParticipants(array $credits): array
    {
        $participants = [];
        $baseImageUrl = 'https://image.tmdb.org/t/p/w185';

        foreach (['cast', 'crew'] as $key) {
            foreach ($credits[$key] ?? [] as $member) {
                $participants[] = [
                    'nom' => $member['name'],
                    'role' => $member['character'] ?? $member['job'] ?? 'Non spécifié',
                    'photo' => isset($member['profile_path']) ? $baseImageUrl . $member['profile_path'] : null,
                ];
            }
        }

        return $participants;
    }

    private function getProducer(array $crew): ?string
    {
        foreach ($crew as $member) {
            if (($member['job'] ?? '') === 'Producer') {
                return $member['name'];
            }
        }
        return null;
    }

    public function getCommentairesByTMDB(int $idTMDB): array
    {
        try {
            $pdo = $this->getConnection();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT c.contenu, u.pseudo, u.photoProfil 
                FROM vhs_commentaire c 
                JOIN vhs_utilisateur u ON c.idUtilisateur = u.idUtilisateur 
                WHERE c.idTMDB = :idTMDB";

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['idTMDB' => $idTMDB]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération des commentaires : ' . $e->getMessage());
            return [];
        }
    }
}

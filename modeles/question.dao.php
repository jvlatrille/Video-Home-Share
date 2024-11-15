<?php

class QuestionDao {
    private ?PDO $pdo;

    // Getters et setters
    public function getPdo(): ?PDO {
        return $this->pdo;
    }

    public function setPdo(?PDO $pdo): void {
        $this->pdo = $pdo;
    }

    // Fonction pour afficher une question
    public function find(int $id): ?question {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."question q
        WHERE q.idQuestion = :id";

        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array('id' => $id));    
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = $pdoStatement->fetch(PDO::FETCH_ASSOC);  

        if (!$resultat) {
            // Si aucun résultat n'est trouvé
            var_dump("Aucune question trouvée.");
            return null;
        }

        // Hydrate l'objet question avec les données récupérées
        $question = $this->hydrate($resultat);
        return $question;
    }

    // Fonction pour afficher toutes les questions d'un quizz
    public function findAll(int $idQuizz): ?array {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."question q
        WHERE q.idQuizz = :id";

        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array('id' => $idQuizz));    
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $resultats = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);  

        if (!$resultats) {
            // Si aucun résultat n'est trouvé
            var_dump("Aucune question trouvée pour ce quizz.");
            return null;
        }

        // Hydrate toutes les questions récupérées
        $questions = $this->hydrateAll($resultats);
        return $questions;
    }

    // Fonction pour hydrater une seule question
    public function hydrate(array $data): ?question {
        $question = new question(
            $data['idQuestion'],  // idQuestion
            $data['contenu'],     // contenu
            $data['numero'],      // numero
            $data['nvDifficulte'],// niveau de difficulté
            $data['bonneReponse'] // bonneReponse
        );
        return $question;
    }

    // Fonction pour hydrater un tableau de questions
    public function hydrateAll(array $data): array {
        $questions = [];
        foreach ($data as $row) {
            $questions[] = $this->hydrate($row);
        }
        return $questions;
    }
}
?>

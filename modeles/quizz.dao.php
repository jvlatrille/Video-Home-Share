<?php

class QuizzDao {
    private ?PDO $pdo;

    // Getters et setters
    public function getPdo(): ?PDO {
        return $this->pdo;
    }

    public function setPdo(?PDO $pdo): void {
        $this->pdo = $pdo;
    }

    // Fonction pour afficher un quizz par son ID
    public function find(int $id): ?Quizz {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."quizz q
        WHERE q.idQuizz = :id";

        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array('id' => $id));    
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = $pdoStatement->fetch(PDO::FETCH_ASSOC);  

        if (!$resultat) {
            // Si aucun résultat n'est trouvé
            var_dump("Aucun quizz trouvé.");
            return null;
        }

        // Hydrate l'objet quizz avec les données récupérées
        $quizz = $this->hydrate($resultat);
        return $quizz;
    }

    // Fonction pour afficher tous les quizz
    public function findAll(): ?array {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."quizz q";

        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();    
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $resultats = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);  

        if (!$resultats) {
            // Si aucun résultat n'est trouvé
            var_dump("Aucun quizz trouvé.");
            return null;
        }

        // Hydrate tous les quizz récupérés
        $quizzListe = $this->hydrateAll($resultats);
        return $quizzListe;
    }

    // Fonction pour afficher tous les quizz d'un thème donné
    public function findByTheme(string $theme): ?array {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."quizz q
        WHERE q.theme = :theme";

        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array('theme' => $theme));    
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $resultats = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);  

        if (!$resultats) {
            // Si aucun résultat n'est trouvé
            var_dump("Aucun quizz trouvé pour ce thème.");
            return null;
        }

        // Hydrate tous les quizz récupérés
        $quizzListe = $this->hydrateAll($resultats);
        return $quizzListe;
    }

    // Fonction pour hydrater un seul quizz
    public function hydrate(array $data): ?Quizz {
        $quizz = new Quizz(
            $data['idQuizz'],     // idQuizz
            $data['nom'],         // nom
            $data['theme'],       // theme
            $data['nbQuestion'],  // nbQuestion
            $data['difficulte'],  // difficulte
            $data['meilleurJ']    // meilleurJ
        );
        return $quizz;
    }

    // Fonction pour hydrater plusieurs quizz
    public function hydrateAll(array $data): array {
        $quizzListe = [];
        foreach ($data as $row) {
            $quizzListe[] = $this->hydrate($row);
        }
        return $quizzListe;
    }

    // Fonction pour ajouter un nouveau quizz dans la base de données
    public function add(Quizz $quizz): bool {
        $sql = "INSERT INTO ".PREFIXE_TABLE."quizz (nom, theme, nbQuestion, difficulte, meilleurJ) 
                VALUES (:nom, :theme, :nbQuestion, :difficulte, :meilleurJ)";

        $pdoStatement = $this->pdo->prepare($sql);
        return $pdoStatement->execute([
            'nom' => $quizz->getNom(),
            'theme' => $quizz->getTheme(),
            'nbQuestion' => $quizz->getNbQuestion(),
            'difficulte' => $quizz->getDifficulte(),
            'meilleurJ' => $quizz->getMeilleurJ()
        ]);
    }

    // Fonction pour mettre à jour un quizz dans la base de données
    public function update(Quizz $quizz): bool {
        $sql = "UPDATE ".PREFIXE_TABLE."quizz 
                SET nom = :nom, theme = :theme, nbQuestion = :nbQuestion, difficulte = :difficulte, meilleurJ = :meilleurJ
                WHERE idQuizz = :idQuizz";

        $pdoStatement = $this->pdo->prepare($sql);
        return $pdoStatement->execute([
            'idQuizz' => $quizz->getIdQuizz(),
            'nom' => $quizz->getNom(),
            'theme' => $quizz->getTheme(),
            'nbQuestion' => $quizz->getNbQuestion(),
            'difficulte' => $quizz->getDifficulte(),
            'meilleurJ' => $quizz->getMeilleurJ()
        ]);
    }

    // Fonction pour supprimer un quizz de la base de données
    public function delete(int $idQuizz): bool {
        $sql = "DELETE FROM ".PREFIXE_TABLE."quizz WHERE idQuizz = :idQuizz";

        $pdoStatement = $this->pdo->prepare($sql);
        return $pdoStatement->execute(['idQuizz' => $idQuizz]);
    }
}
?>

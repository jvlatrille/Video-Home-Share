<?php

class QuizzDao {
    private ?PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Getters et setters
    public function getPdo(): ?PDO {
        return $this->pdo;
    }

    public function setPdo(?PDO $pdo): void {
        $this->pdo = $pdo;
    }

    // Méthode pour trouver un quizz par son ID
    public function find(?int $id): ?Quizz {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "quizz WHERE idQuizz = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['id' => $id]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $quizzData = $pdoStatement->fetch();

        return $quizzData ? $this->hydrate($quizzData) : null;
    }

    // Méthode pour récupérer tous les quizz
    public function findAll(): ?array {
        $sql = "SELECT q.*, u.Pseudo FROM " . PREFIXE_TABLE . "quizz q JOIN " . PREFIXE_TABLE . "utilisateur u on q.idCreateur = u.idUtilisateur";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);

        $resultats = $pdoStatement->fetchAll();
        return $this->hydrateAll($resultats);
    }

    // Méthode pour hydrater un quizz
    public function hydrate(array $data): ?Quizz {
        $quizz = new Quizz(
            $data['idQuizz'],     // idQuizz
            $data['nom'],         // nom
            $data['theme'],       // theme
            $data['nbQuestion'],  // nbQuestion
            $data['difficulte'],  // difficulte
            $data['idCreateur']   // idCreateur
        );
        $quizz->setPseudo($data['Pseudo']); // Set the pseudo
        return $quizz;
    }

    // Méthode pour hydrater une liste de quizz
    public function hydrateAll(array $resultats): ?array {
        $quizzListe = [];
        foreach ($resultats as $row) {
            $quizzListe[] = $this->hydrate($row);
        }
        return $quizzListe;
    }
    public function add(Quizz $quizz): int|false {
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO " . PREFIXE_TABLE . "quizz (nom, theme, nbQuestion, difficulte, idCreateur) 
                 VALUES (:nom, :theme, :nbQuestion, :difficulte, :idCreateur)"
            );
            $stmt->execute([
                ':nom' => $quizz->getNom(),
                ':theme' => $quizz->getTheme(),
                ':nbQuestion' => $quizz->getNbQuestion(),
                ':difficulte' => $quizz->getDifficulte(),
                ':idCreateur' => $quizz->getIdCreateur()
            ]);

            // Retourner l'ID généré après l'insertion
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout du quizz : " . $e->getMessage());
            return false;
        }
    }
    
}


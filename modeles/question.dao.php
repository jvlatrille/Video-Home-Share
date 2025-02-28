<?php

class QuestionDao {
    private ?PDO $pdo;

    // Constructeur pour initialiser la connexion PDO
    public function __construct(?PDO $pdo = null) {
        // Si l'objet PDO n'est pas fourni, on essaie de créer une nouvelle connexion PDO
        if ($pdo === null) {
            try {
                $this->pdo = new PDO('mysql:host=localhost;dbname=nom_de_base_de_donnees', 'utilisateur', 'mot_de_passe');
 
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                // Gestion des erreurs de connexion
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        } else {
            $this->pdo = $pdo;
        }
    }

    // Getters et setters
    public function getPdo(): ?PDO {
        return $this->pdo;
    }

    public function setPdo(?PDO $pdo): void {
        $this->pdo = $pdo;
    }
    
    public function findQuestionByQuizzAndNumero(int $idQuizz, int $numero): ?question {
        // Requête pour récupérer une question spécifique du quizz
        $sql = "SELECT q.* FROM ".PREFIXE_TABLE."question q
                INNER JOIN ".PREFIXE_TABLE."portersur p ON p.idQuestion = q.idQuestion
                WHERE p.idQuizz = :idQuizz AND q.numero = :numero
                LIMIT 1"; // Limite à 1 question, correspond au numéro demandé
    
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute([
            'idQuizz' => $idQuizz,
            'numero' => $numero
        ]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = $pdoStatement->fetch(PDO::FETCH_ASSOC);
    
        if (!$resultat) {
            // Si aucune question n'est trouvée
            return null;
        }
    
        // Hydrate l'objet question avec les données récupérées
        return $this->hydrate($resultat);
    }
    
    
    // Fonction pour afficher une question
    public function find(int $id): ?question {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."question q WHERE q.idQuestion = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['id' => $id]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = $pdoStatement->fetch(PDO::FETCH_ASSOC);

        if (!$resultat) {
            // Si aucune question n'est trouvée
            var_dump("Aucune question trouvée.");
            return null;
        }

        // Hydrate l'objet question avec les données récupérées
        return $this->hydrate($resultat);
    }

    // Fonction pour afficher toutes les questions d'un quizz
    public function findAll(int $idQuizz): ?array {
        $sql = "SELECT * FROM ".PREFIXE_TABLE."question q INNER JOIN ".PREFIXE_TABLE."portersur p ON p.idQuestion = q.idQuestion
        WHERE p.idQuizz = :idQuizz";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['idQuizz' => $idQuizz]); 
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $resultats = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

        if (!$resultats) {
            // Si aucune question n'est trouvée pour ce quizz
            var_dump("Aucune question trouvée pour ce quizz.");
            return null;
        }

        // Hydrate toutes les questions récupérées
        return $this->hydrateAll($resultats);
    }

    // Fonction pour hydrater une seule question
    public function hydrate(array $data): ?question {
        return new question(
            $data['idQuestion'],  // idQuestion
            $data['contenu'],     // contenu
            $data['numero'],      // numéro
            $data['nvDifficulte'],// niveau de difficulté
            $data['bonneReponse'], // bonne réponse
            $data['cheminImage'],
            $data['mauvaiseReponse1'],
            $data['mauvaiseReponse2'],
            $data['mauvaiseReponse3']
        );
    }

    // Fonction pour hydrater un tableau de questions
    public function hydrateAll(array $data): array {
        $questions = [];
        foreach ($data as $row) {
            $questions[] = $this->hydrate($row);
        }
        return $questions;
    }
    public function findFirstQuestionByQuizz(int $idQuizz): ?question {
        // Requête pour récupérer la première question du quizz
        $sql = "SELECT q.* FROM ".PREFIXE_TABLE."question q
                INNER JOIN ".PREFIXE_TABLE."portersur p ON p.idQuestion = q.idQuestion
                WHERE p.idQuizz = :idQuizz
                LIMIT 1"; // Limite à 1 question, la première
    
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['idQuizz' => $idQuizz]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = $pdoStatement->fetch(PDO::FETCH_ASSOC);
    
        if (!$resultat) {
            // Si aucune question n'est trouvée
            return null;
        }
    
        // Hydrate l'objet question avec les données récupérées
        return $this->hydrate($resultat);
    }
    // Fonction pour ajouter une question à un quizz
    public function add(question $question): bool {
        $sql = "INSERT INTO " . PREFIXE_TABLE . "question 
                    (contenu, numero, nvDifficulte, bonneReponse, cheminImage, mauvaiseReponse1, mauvaiseReponse2, mauvaiseReponse3) 
                VALUES 
                    (:contenu, :numero, :nvDifficulte, :bonneReponse, :cheminImage, :mauvaiseReponse1, :mauvaiseReponse2, :mauvaiseReponse3)";
        $stmt = $this->pdo->prepare($sql);
    
        return $stmt->execute([
            'contenu' => $question->getContenu(),
            'numero' => $question->getNumero(), // Assurez-vous de passer cette valeur correctement ici
            'nvDifficulte' => $question->getNvDifficulte(),
            'bonneReponse' => $question->getBonneReponse(),
            'cheminImage' => $question->getcheminImage(),
            'mauvaiseReponse1' => $question->getMauvaiseReponse1(),
            'mauvaiseReponse2' => $question->getMauvaiseReponse2(),
            'mauvaiseReponse3' => $question->getMauvaiseReponse3()
        ]);
    }
    

    
    public function update(question $question): bool {
        $sql = "UPDATE ".PREFIXE_TABLE."question SET contenu = :contenu, numero = :numero, nvDifficulte = :nvDifficulte, 
                bonneReponse = :bonneReponse, cheminImage = :cheminImage, mauvaiseReponse1 = :mauvaiseReponse1, 
                mauvaiseReponse2 = :mauvaiseReponse2, mauvaiseReponse3 = :mauvaiseReponse3 
                WHERE idQuestion = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'contenu' => $question->getContenu(),
            'numero' => $question->getNumero(),
            'nvDifficulte' => $question->getNvDifficulte(),
            'bonneReponse' => $question->getBonneReponse(),
            'cheminImage' => $question->getcheminImage(),
            'mauvaiseReponse1' => $question->getMauvaiseReponse1(),
            'mauvaiseReponse2' => $question->getMauvaiseReponse2(),
            'mauvaiseReponse3' => $question->getMauvaiseReponse3(),
            'id' => $question->getIdQuestion()
        ]);
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM ".PREFIXE_TABLE."question WHERE idQuestion = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    public function getLastInsertId(): int
{
    return $this->pdo->lastInsertId();
}
}



?>

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
                error_log("Erreur de connexion à la base de données : " . $e->getMessage());
                $this->afficherErreur("Impossible de se connecter à la base de données.");
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
            $this->afficherErreur("Aucune première question trouvée pour ce quizz.");
        }          
    
        // Hydrate l'objet question avec les données récupérées
        return $this->hydrate($resultat);
    }
    
    
    // Fonction pour afficher une question
    public function find(int $id): ?question {
        $sql = "SELECT * 
                FROM ".PREFIXE_TABLE."question q 
                INNER JOIN ".PREFIXE_TABLE."portersur p ON p.idQuestion = q.idQuestion
                WHERE p.idQuestion = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['id' => $id]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = $pdoStatement->fetch(PDO::FETCH_ASSOC);

        if (!$resultat) {
            $this->afficherErreur("Aucune question trouvée.");
        }        

        // Hydrate l'objet question avec les données récupérées
        return $this->hydrate($resultat);
    }

    // Fonction pour afficher toutes les questions d'un quizz
    public function findAll(int $idQuizz): ?array {
        $sql = "SELECT * 
                FROM ".PREFIXE_TABLE."question q 
                INNER JOIN ".PREFIXE_TABLE."portersur p ON p.idQuestion = q.idQuestion 
                WHERE p.idQuizz = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['id' => $idQuizz]); 
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $resultats = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

        if (!$resultats) {
            $this->afficherErreur("Aucune question trouvée pour ce quizz.");
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
            $data['mauvaiseReponse1'],
            $data['mauvaiseReponse2'],
            $data['mauvaiseReponse3'],
            $data['idQuizz'] ?? null
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
            $this->afficherErreur("Aucune première question trouvée pour ce quizz.");
        }        
    
        // Hydrate l'objet question avec les données récupérées
        return $this->hydrate($resultat);
    }
    // Fonction pour ajouter une question à un quizz
    public function add(question $question): bool {
        $sql = "INSERT INTO " . PREFIXE_TABLE . "question 
                    (contenu, numero, nvDifficulte, bonneReponse,  mauvaiseReponse1, mauvaiseReponse2, mauvaiseReponse3) 
                VALUES 
                    (:contenu, :numero, :nvDifficulte, :bonneReponse,  :mauvaiseReponse1, :mauvaiseReponse2, :mauvaiseReponse3)";
        $stmt = $this->pdo->prepare($sql);
    
        return $stmt->execute([
            'contenu' => $question->getContenu(),
            'numero' => $question->getNumero(), // Assurez-vous de passer cette valeur correctement ici
            'nvDifficulte' => $question->getNvDifficulte(),
            'bonneReponse' => $question->getBonneReponse(),
            'mauvaiseReponse1' => $question->getMauvaiseReponse1(),
            'mauvaiseReponse2' => $question->getMauvaiseReponse2(),
            'mauvaiseReponse3' => $question->getMauvaiseReponse3()
        ]);
    }
    

    
    public function update(question $question): bool {
        $sql = "UPDATE ".PREFIXE_TABLE."question SET contenu = :contenu, numero = :numero, nvDifficulte = :nvDifficulte, 
                bonneReponse = :bonneReponse, mauvaiseReponse1 = :mauvaiseReponse1, 
                mauvaiseReponse2 = :mauvaiseReponse2, mauvaiseReponse3 = :mauvaiseReponse3 
                WHERE idQuestion = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'contenu' => $question->getContenu(),
            'numero' => $question->getNumero(),
            'nvDifficulte' => $question->getNvDifficulte(),
            'bonneReponse' => $question->getBonneReponse(),
            'mauvaiseReponse1' => $question->getMauvaiseReponse1(),
            'mauvaiseReponse2' => $question->getMauvaiseReponse2(),
            'mauvaiseReponse3' => $question->getMauvaiseReponse3(),
            'id' => $question->getIdQuestion()
        ]);
    }

    public function delete(int $id): bool {
        $idQuiz = $this->findQuizByQuestion($id);

        $sql = "UPDATE ".PREFIXE_TABLE."quizz 
                           SET nbQuestion = nbQuestion - 1 
                           WHERE idQuizz = :idQuiz";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['idQuiz' => $idQuiz]);

        $sql = "DELETE q, p
                FROM  ".PREFIXE_TABLE."question AS q
                LEFT JOIN  ".PREFIXE_TABLE."portersur AS p ON q.idQuestion = p.idQuestion
                WHERE q.idQuestion = :id;";
        $pdoStatement = $this->pdo->prepare($sql);
        return $pdoStatement->execute(['id' => $id]);
    }

    public function findQuizByQuestion(int $id)
    {
        $sql = "SELECT idQuizz
                FROM  ".PREFIXE_TABLE."portersur
                WHERE idQuestion = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['id' => $id]);

        $result = $pdoStatement->fetch(PDO::FETCH_ASSOC);
        return $result['idQuizz'];
    }    

    public function nbQuestion(int $id) {
        $sql = "SELECT COUNT(idQuestion) AS nombre_questions
                FROM  ".PREFIXE_TABLE."portersur AS p
                WHERE p.idQuizz = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['id' => $id]);
        
        $result = $pdoStatement->fetch(PDO::FETCH_ASSOC);
        return $result['nombre_questions'];
    }
    
    public function liee($idQuizz, $idQuestion)
    {
        $sql = "INSERT INTO vhs_portersur (idQuizz, idQuestion) VALUES (:idQuizz, :idQuestion)"; //sale fou va
        $pdoStatement = $this->getPdo()->prepare($sql);
        return $pdoStatement->execute([
                                       'idQuizz' => $idQuizz,
                                       'idQuestion' => $idQuestion]);
    }

    public function getLastInsertId(): int
    {
        return $this->pdo->lastInsertId();
    }
}



?>

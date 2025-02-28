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
        $sql = "SELECT q.*, u.pseudo 
                FROM " . PREFIXE_TABLE . "quizz q 
                JOIN " . PREFIXE_TABLE . "utilisateur u on q.idCreateur = u.idUtilisateur";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);

        $resultats = $pdoStatement->fetchAll();
        return $this->hydrateAll($resultats);
    }

    // Méthode pour hydrater un quizz
    public function hydrate(array $data): ?Quizz {
        $managerUtilisateur = new UtilisateurDao($this->getPdo());
        $pseudo = $managerUtilisateur->getPseudo($data['idCreateur']);
        $quizz = new Quizz(
            $data['idQuizz'],     // idQuizz
            $data['nom'],         // nom
            $data['theme'],       // theme
            $data['nbQuestion'],  // nbQuestion
            $data['difficulte'],   // difficulte
            $data['idCreateur'],   // idCreateur
            $pseudo,        //pseudo
            $data['image']   // image
        );
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
        $sql = "INSERT INTO " . PREFIXE_TABLE . "quizz (nom, theme, nbQuestion, difficulte, idCreateur, image) 
                VALUES (:nom, :theme, :nbQuestion, :difficulte, :idCreateur, :image)";

        $pdoStatement = $this->pdo->prepare($sql);
        $reussite = $pdoStatement->execute([
            ':nom' => $quizz->getNom(),
            ':theme' => $quizz->getTheme(),
            ':nbQuestion' => $quizz->getNbQuestion(),
            ':difficulte' => $quizz->getDifficulte(),
            ':idCreateur' => $quizz->getIdCreateur(),
            ':image' => $quizz->getImage()
        ]);

        return $this->pdo->lastInsertId();
    }

    public function ajoutImage($idQuizz, $fileName)
    {
        $sql = "UPDATE " . PREFIXE_TABLE . "quizz 
                SET image = :fileName 
                WHERE idQuizz = :idQuizz";
        $pdoStatement = $this->pdo->prepare($sql);
        $reussite = $pdoStatement->execute(['fileName' => $fileName, 'idQuizz' => $idQuizz]);

        return $reussite;
    }
    
    public function findId($id)
    {
        $sql = "SELECT q.*, u.pseudo 
                FROM " . PREFIXE_TABLE . "quizz q 
                JOIN " . PREFIXE_TABLE . "utilisateur u on q.idCreateur = u.idUtilisateur 
                WHERE q.idCreateur = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['id' => $id]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);

        $resultats = $pdoStatement->fetchAll();
        return $this->hydrateAll($resultats);
    }

    public function update($quiz)
    {
        $id = $quiz->getIdQuizz();
        $nom = $quiz->getNom();
        $theme = $quiz->getTheme();
        $nbQuestion = $quiz->getNbQuestion();
        $difficulte = $quiz->getDifficulte();
        $image = $quiz->getImage();

        $sql = "UPDATE " . PREFIXE_TABLE . "quizz 
                SET nom = :nom, theme = :theme, nbQuestion = :nbQuestion, difficulte = :difficulte, image = :image 
                WHERE idQuizz = :id";
        $pdoStatement = $this->getPdo()->prepare($sql);

        return $pdoStatement->execute(['nom' => $nom,
                                       'theme' => $theme,
                                       'nbQuestion' => $nbQuestion,
                                       'difficulte' => $difficulte,
                                       'image' => $image,
                                       'id' => $id]);
    }

    public function delete($id)
    {
        $sql = "DELETE q, p, t
                FROM  ".PREFIXE_TABLE."quizz AS q
                LEFT JOIN  ".PREFIXE_TABLE."portersur AS p ON q.idQuizz = p.idQuizz
                LEFT JOIN  ".PREFIXE_TABLE."question AS t ON p.idQuestion = t.idQuestion
                WHERE q.idQuizz = :id;";
        
        $pdoStatement = $this->getPdo()->prepare($sql);
        return $pdoStatement->execute(['id' => $id]);
    }
}


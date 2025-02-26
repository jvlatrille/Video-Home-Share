<?php

class ControllerQuestion extends Controller
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    // Fonction pour lister toutes les questions d'un quizz
    public function listerQuestion()
    {
        $idQuizz = isset($_GET['id']) ? $_GET['id'] : null;

        // Récupère toutes les questions du quizz
        $managerQuestion = new QuestionDao($this->getPdo());
        $questionListe = $managerQuestion->findAll($idQuizz);

        // Générer la vue
        $template = $this->getTwig()->load('questionListe.html.twig');
        echo $template->render(['questionListe' => $questionListe]);
    }

    // Fonction pour afficher une question spécifique
    public function afficherQuestion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $idQuizz = isset($_GET['idQuizz']) ? (int)$_GET['idQuizz'] : null;
        $numero = isset($_GET['numero']) ? (int)$_GET['numero'] : 1;

        if (!$idQuizz) {
            echo "ID du quizz manquant ou invalide.";
            return;
        }

        // Vérifier si une réponse a été soumise
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reponseChoisie'])) {
            $reponseChoisie = $_POST['reponseChoisie'];

            // Récupère la question pour comparer la réponse
            $managerQuestion = new QuestionDao($this->getPdo());
            $question = $managerQuestion->findQuestionByQuizzAndNumero($idQuizz, $numero);

            if ($question && $reponseChoisie === $question->getBonneReponse()) {
                $_SESSION['score']++; // Incrémenter le score
            }

            // Redirige vers la question suivante
            header("Location: index.php?controleur=question&methode=afficherQuestion&idQuizz=$idQuizz&numero=" . ($numero + 1));
            exit;
        }


        // Initialiser le score pour la première question
        if ($numero === 1) {
            $_SESSION['score'] = 0;
        }

        // Récupérer la question courante
        $managerQuestion = new QuestionDao($this->getPdo());
        $question = $managerQuestion->findQuestionByQuizzAndNumero($idQuizz, $numero);

        if (!$question) {
            // Si aucune question n'est trouvée, afficher le score final
            header("Location: index.php?controleur=question&methode=afficherScore&idQuizz=$idQuizz");
            exit;
        }

        // Mélanger les réponses
        $reponses = [
            $question->getBonneReponse(),
            $question->getMauvaiseReponse1(),
            $question->getMauvaiseReponse2(),
            $question->getMauvaiseReponse3()
        ];
        shuffle($reponses);

        $managerQuizz = new QuizzDao($this->getPdo());
        $quizz = $managerQuizz->find($idQuizz);
        $nbTotalQuestions = $quizz->getNbQuestion();
        $image = $quizz->getImage();
        // Générer la vue
        $template = $this->getTwig()->load('question.html.twig');
        echo $template->render([
            'question' => $question,
            'reponses' => $reponses,
            'idQuizz' => $idQuizz,
            'numero' => $numero,
            'score' => $_SESSION['score'],
            'nbTotalQuestions' => $nbTotalQuestions,  // Ajout de cette ligne
            'image' => $image
        ]);
    }


    // Fonction pour ajouter une question à un quizz
    public function ajouterQuestions()
    {
        $idQuizz = $_GET['idQuizz'] ?? null; // Récupérer 'idQuizz' de l'URL
        $nbQuestion = $_GET['nbQuestion'] ?? 1; // Récupérer 'nbQuestion' de l'URL

        // Vérification si les paramètres existent
        if ($idQuizz === null) {
            die("L'identifiant du quizz est requis !");
        }

        $managerQuizz = new QuizzDao($this->getPdo());
        $quizz = $managerQuizz->find($idQuizz);
        $image = $quizz->getImage();

        // Appeler le template avec les variables nécessaires
        $template = $this->getTwig()->load('questionAjouter.html.twig');
        echo $template->render([
            'idQuizz' => $idQuizz,
            'nbQuestion' => $nbQuestion,
            'image' => $image
        ]);
    }


    public function saveQuestions()
{
    // Vérifie si une session est active
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $idQuizz = $_POST['idQuizz'] ?? null; // ID du quizz provenant du formulaire
    $questionsData = $_POST['questions'] ?? []; // Données des questions soumises

    // Si l'ID du quizz est manquant
    if (!$idQuizz) {
        die("L'identifiant du quizz est requis !");
    }

    $managerQuestion = new QuestionDao($this->getPdo());

    foreach ($questionsData as $questionData) {
        // Extraction des données pour chaque question
        $contenu = $questionData['contenu'] ?? '';
        $numero = $questionData['numero'] ?? 1;
        $nvDifficulte = $questionData['nvDifficulte'] ?? '';
        $bonneReponse = $questionData['bonneReponse'] ?? '';
        $mauvaiseReponse1 = $questionData['mauvaiseReponse1'] ?? '';
        $mauvaiseReponse2 = $questionData['mauvaiseReponse2'] ?? '';
        $mauvaiseReponse3 = $questionData['mauvaiseReponse3'] ?? '';
        $cheminImage = $questionData['cheminImage'] ?? '';

        // Création de l'objet Question
        $question = new Question(
            null, // L'ID sera généré automatiquement par la base de données
            $contenu,
            $numero,
            $nvDifficulte,
            $bonneReponse,
            $cheminImage,
            $mauvaiseReponse1,
            $mauvaiseReponse2,
            $mauvaiseReponse3
        );

        // Ajout de la question
        if ($managerQuestion->add($question)) {
            // Récupérer l'ID de la question ajoutée
            $idQuestion = $managerQuestion->getLastInsertId();

            // Lier la question au quizz dans la table vhs_portersur
            $sql = "INSERT INTO vhs_portersur (idQuizz, idQuestion) VALUES (:idQuizz, :idQuestion)";
            $stmt = $this->getPdo()->prepare($sql);
            $stmt->execute([
                'idQuizz' => $idQuizz,
                'idQuestion' => $idQuestion
            ]);
        } else {
            // Si l'ajout échoue, afficher une erreur
            die("Erreur lors de l'ajout de la question.");
        }
    }

    // Redirection après l'ajout des questions
    header('Location: index.php?controleur=quizz&methode=listerQuizz');
    exit;
}




    // Fonction pour modifier une question
    public function modifierQuestion()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        // Récupère la question
        $managerQuestion = new QuestionDao($this->getPdo());
        $question = $managerQuestion->find($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupère les données du formulaire
            $contenu = $_POST['contenu'] ?? $question->getContenu();
            $numero = $_POST['numero'] ?? $question->getNumero();
            $nvDifficulte = $_POST['nvDifficulte'] ?? $question->getNvDifficulte();
            $bonneReponse = $_POST['bonneReponse'] ?? $question->getBonneReponse();
            $cheminImage = $_POST['cheminImage'] ?? $question->getcheminImage();
            $mauvaiseReponse1 = $_POST['mauvaiseReponse1'] ?? $question->getMauvaiseReponse1();
            $mauvaiseReponse2 = $_POST['mauvaiseReponse2'] ?? $question->getMauvaiseReponse2();
            $mauvaiseReponse3 = $_POST['mauvaiseReponse3'] ?? $question->getMauvaiseReponse3();

            // Met à jour l'objet Question
            $question->setContenu($contenu);
            $question->setNumero($numero);
            $question->setNvDifficulte($nvDifficulte);
            $question->setBonneReponse($bonneReponse);
            $question->setcheminImage($cheminImage);
            $question->setMauvaiseReponse1($mauvaiseReponse1);
            $question->setMauvaiseReponse2($mauvaiseReponse2);
            $question->setMauvaiseReponse3($mauvaiseReponse3);

            // Met à jour la question dans la base de données
            if ($managerQuestion->update($question)) {
                // Redirige vers la liste des questions
                header('Location: index.php?controleur=question&methode=listerQuestion&idQuizz=' . $question->getIdQuizz());
                exit;
            } else {
                // Erreur de mise à jour
                echo "Erreur lors de la modification de la question.";
            }
        }

        // Générer la vue
        $template = $this->getTwig()->load('question_modifier.html.twig');
        echo $template->render(['question' => $question]);
    }

    // Fonction pour supprimer une question
    public function supprimerQuestion()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        // Supprime la question
        $managerQuestion = new QuestionDao($this->getPdo());
        if ($managerQuestion->delete($id)) {
            // Redirige vers la liste des questions
            header('Location: index.php?controleur=question&methode=listerQuestion&idQuizz=' . $_GET['idQuizz']);
            exit;
        } else {
            // Erreur de suppression
            echo "Erreur lors de la suppression de la question.";
        }
    }
    public function afficherScore()
{
    // Récupère le score de la session
    $score = $_SESSION['score'] ?? 0;

    // Récupère l'ID du quizz
    $idQuizz = isset($_GET['idQuizz']) ? (int)$_GET['idQuizz'] : null;

    if (!$idQuizz) {
        echo "ID du quizz manquant.";
        return;
    }

    // Récupère le nombre total de questions du quizz
    $managerQuizz = new QuizzDao($this->getPdo());
    $quizz = $managerQuizz->find($idQuizz);
    $nbTotalQuestions = $quizz->getNbQuestion();

    // Générer la vue pour afficher le score
    $template = $this->getTwig()->load('quizzResultat.html.twig');
    echo $template->render([
        'score' => $score,
        'nbTotalQuestions' => $nbTotalQuestions, // Passage du nombre total de questions à la vue
        'idQuizz' => $idQuizz
    ]);

    // Réinitialiser le score pour un futur quizz
    unset($_SESSION['score']);
}

    public function afficherQuestionAjax()
    {
        $idQuizz = isset($_GET['idQuizz']) ? (int)$_GET['idQuizz'] : null;
        $numero = isset($_GET['numero']) ? (int)$_GET['numero'] : 1;

        if (!$idQuizz) {
            echo json_encode(["error" => "ID du quizz manquant ou invalide."]);
            return;
        }

        $managerQuestion = new QuestionDao($this->getPdo());
        $question = $managerQuestion->findQuestionByQuizzAndNumero($idQuizz, $numero);

        if (!$question) {
            echo json_encode(["end" => true]);
            return;
        }

        $reponses = [
            ["text" => $question->getBonneReponse(), "correct" => true],
            ["text" => $question->getMauvaiseReponse1(), "correct" => false],
            ["text" => $question->getMauvaiseReponse2(), "correct" => false],
            ["text" => $question->getMauvaiseReponse3(), "correct" => false]
        ];
        shuffle($reponses);

        $difficultyClass = '';
        if ($question->getNvDifficulte() == 'Facile') {
            $difficultyClass = 'text-success';
        } elseif ($question->getNvDifficulte() == 'Moyen') {
            $difficultyClass = 'text-warning';
        } elseif ($question->getNvDifficulte() == 'Difficile') {
            $difficultyClass = 'text-danger';
        }

        // Ajouter le chemin de l'image dans la réponse
        $cheminImage = $question->getCheminImage();

        echo json_encode([
            "question" => $question->getContenu(),
            "reponses" => $reponses,
            "difficulty" => $question->getNvDifficulte(),
            "difficultyClass" => $difficultyClass,
            "numero" => $numero,
            "image" => $cheminImage // Ajout du chemin de l'image
        ]);
    }
    
}







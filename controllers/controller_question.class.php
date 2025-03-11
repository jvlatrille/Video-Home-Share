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

        $breadcrumb = [
            ['title' => 'Accueil', 'url' => 'index.php'],
            ['title' => 'Liste des quiz', 'url' => 'index.php?controleur=Quizz&methode=listerQuizz'],
            ['title' => $quizz->getNom(), 'url' => 'index.php?controleur=Quizz&methode=afficherQuizz&id=' . $idQuizz],
            ['title' => 'Question ', 'url' => 'index.php?controleur=question&methode=afficherQuestion&idQuizz=' . $idQuizz . '&numero=' . $numero]
        ];

        // Générer la vue
        $template = $this->getTwig()->load('question.html.twig');
        echo $template->render([
            'question' => $question,
            'reponses' => $reponses,
            'idQuizz' => $idQuizz,
            'numero' => $numero,
            'score' => $_SESSION['score'],
            'nbTotalQuestions' => $nbTotalQuestions,  // Ajout de cette ligne
            'image' => $image,
            'breadcrumb' => $breadcrumb
        ]);
    }


    // Fonction pour ajouter une question à un quizz
    public function ajouterQuestions()
    {
        $idQuizz = $_GET['idQuizz'] ?? null; // Récupérer 'idQuizz' de l'URL
        $nbQuestion = $_GET['nbQuestion'] ?? 1; // Récupérer 'nbQuestion' de l'URL

        // Vérification si les paramètres existent
        if ($idQuizz === null) {
            $this->afficherErreur("L'identifiant du quizz est requis !");
            exit();            
        }

        $managerQuizz = new QuizzDao($this->getPdo());
        $quizz = $managerQuizz->find($idQuizz);
        $image = $quizz->getImage();

        $breadcrumb = [
            ['title' => 'Accueil', 'url' => 'index.php'],
            ['title' => 'Liste des quiz', 'url' => 'index.php?controleur=Quizz&methode=listerQuizz'],
            ['title' => 'Ajouter un quiz', 'url' => 'index.php?controleur=Quizz&methode=ajouterQuizz'],
            ['title' => 'Ajouter des questions', 'url' => 'index.php?controleur=question&methode=ajouterQuestions&idQuizz='.$idQuizz.'&nbQuestion='.$nbQuestion]
        ];

        // Appeler le template avec les variables nécessaires
        $template = $this->getTwig()->load('questionAjouter.html.twig');
        echo $template->render([
            'idQuizz' => $idQuizz,
            'nbQuestion' => $nbQuestion,
            'image' => $image,
            'breadcrumb' => $breadcrumb
        ]);
    }


    public function saveQuestions()
{
    $idQuizz = $_POST['idQuizz'] ?? null; // ID du quizz provenant du formulaire
    $questionsData = $_POST['questions'] ?? []; // Données des questions soumises

    // Si l'ID du quizz est manquant
    if (!$idQuizz) {
        $this->afficherErreur("L'identifiant du quizz est requis !");
        exit();
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

        // Création de l'objet Question
        $question = new Question(
            null, // L'ID sera généré automatiquement par la base de données
            $contenu,
            $numero,
            $nvDifficulte,
            $bonneReponse,
            $mauvaiseReponse1,
            $mauvaiseReponse2,
            $mauvaiseReponse3,
            $idQuizz
        );

        // Ajout de la question
        if ($managerQuestion->add($question)) {
            // Récupérer l'ID de la question ajoutée
            $idQuestion = $managerQuestion->getLastInsertId();

            // Lier la question au quizz dans la table vhs_portersur
            $managerQuestion->liee($idQuizz, $idQuestion);
        } else {
            // Si l'ajout échoue, afficher une erreur
            $this->afficherErreur("Erreur lors de l'ajout de la question.");
            exit();
        }
    }

    // Redirection après l'ajout des questions
    header('Location: index.php?controleur=quizz&methode=listerQuizz');
    exit;
}


    public function afficherModifierQuestion(?array $message = [], ?int $id = null)
    {
        if ($id === null)
        {
            $id = isset($_GET['id']) ? $_GET['id'] : null;
        }

        if ($id === null) {
            $template = $this->getTwig()->load('quizzModifier.html.twig');
            echo $template->render(['message' => $message]);

        }
        
        //Recupere la notification
        $managerQuestion=New QuestionDao($this->getPdo());
        $questionListe=$managerQuestion->findAll($id);

        //Generer la vue
        $template = $this->getTwig()->load('questionModifier.html.twig');
        
        echo $template->render(['questionListe'=>$questionListe,
                                'message' => $message,
                                'idQuiz' => $id]);
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
            $mauvaiseReponse1 = $_POST['mauvaiseReponse1'] ?? $question->getMauvaiseReponse1();
            $mauvaiseReponse2 = $_POST['mauvaiseReponse2'] ?? $question->getMauvaiseReponse2();
            $mauvaiseReponse3 = $_POST['mauvaiseReponse3'] ?? $question->getMauvaiseReponse3();

            // Met à jour l'objet Question
            $question->setContenu($contenu);
            $question->setNumero($numero);
            $question->setNvDifficulte($nvDifficulte);
            $question->setBonneReponse($bonneReponse);
            $question->setMauvaiseReponse1($mauvaiseReponse1);
            $question->setMauvaiseReponse2($mauvaiseReponse2);
            $question->setMauvaiseReponse3($mauvaiseReponse3);

            // Met à jour la question dans la base de données
            if ($managerQuestion->update($question)) {
                // Redirige vers la liste des questions
                header('Location: index.php?controleur=question&methode=afficherModifierQuestion&id=' . $question->getIdQuizz());
                exit;
            } else {
                // Erreur de mise à jour
                $this->afficherErreur("Erreur lors de la modification de la question.");
                exit();
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
        $managerQuestion = new QuestionDao($this->getPdo());

        $idQuiz = $managerQuestion->findQuizByQuestion($id);
        if((int)$managerQuestion->nbQuestion($idQuiz) == 1)
        {
            $message = ["Vous devez avoir au moins une question"];
            return $this->afficherModifierQuestion($message, $idQuiz);
        }

        // Supprime la question
        if ($managerQuestion->delete($id)) {
            // Redirige vers la liste des questions
            header('Location: index.php?controleur=question&methode=afficherModifierQuestion&id=' . $idQuiz);
            exit;
        } else {
            // Erreur de suppression
            $this->afficherErreur("Erreur lors de la suppression de la question.");
            exit();
        }
    }

    public function rajoutQuestion()
    {  
        $idQuiz = isset($_GET['idQuiz']) ? $_GET['idQuiz'] : null;

        // Récupère la question
        $managerQuizz = new QuizzDao($this->getPdo());
        $num = $managerQuizz->nbQuestion($idQuiz);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupère les données du formulaire
            $contenu = $_POST['contenu'];
            $numero = $num + 1;
            $nvDifficulte = $_POST['nvDifficulte'];
            $bonneReponse = $_POST['bonneReponse'];
            $mauvaiseReponse1 = $_POST['mauvaiseReponse1'];
            $mauvaiseReponse2 = $_POST['mauvaiseReponse2'];
            $mauvaiseReponse3 = $_POST['mauvaiseReponse3'];

            $question = new Question(null, $contenu, $numero, $nvDifficulte, $bonneReponse, $mauvaiseReponse1, $mauvaiseReponse2, $mauvaiseReponse3, $idQuiz);
            $managerQuizz->ajoutQuestion($idQuiz);

            $managerQuestion = new QuestionDao($this->getPdo());
            // Rajout de la question
            if ($managerQuestion->add($question)) {
                // Récupérer l'ID de la question ajoutée
                $idQuestion = $managerQuestion->getLastInsertId();
    
                // Lier la question au quizz dans la table vhs_portersur
                $managerQuestion->liee($idQuiz, $idQuestion);
            } else {
                // Si l'ajout échoue, afficher une erreur
                $this->afficherErreur("Erreur lors de l'ajout de la question.");
                exit();
            }
        }
    
        // Redirection après l'ajout des questions
        header('Location: index.php?controleur=question&methode=afficherModifierQuestion&id=' . $idQuiz);
        exit;
    }

    public function afficherScore()
    {
        // Récupère le score de la session
        $score = $_SESSION['score'] ?? 0;

        // Récupère l'ID du quizz
        $idQuizz = isset($_GET['idQuizz']) ? (int)$_GET['idQuizz'] : null;

    if (!$idQuizz) {
        $this->afficherErreur("ID du quizz manquant.");
        exit();
    }

        // Récupère le nombre total de questions du quizz
        $managerQuizz = new QuizzDao($this->getPdo());
        $quizz = $managerQuizz->find($idQuizz);
        $nbTotalQuestions = $quizz->getNbQuestion();
    $breadcrumb = [
        ['title' => 'Accueil', 'url' => 'index.php'],
        ['title' => 'Liste des quiz', 'url' => 'index.php?controleur=Quizz&methode=listerQuizz'],
        ['title' => $quizz->getNom(), 'url' => 'index.php?controleur=Quizz&methode=afficherQuizz&id=' . $idQuizz],
        ['title' => 'Résultat du quiz', 'url' => 'index.php?controleur=question&methode=afficherScore&idQuizz=' . $idQuizz]
    ];

        // Générer la vue pour afficher le score
        $template = $this->getTwig()->load('quizzResultat.html.twig');
        echo $template->render([
            'score' => $score,
            'nbTotalQuestions' => $nbTotalQuestions, // Passage du nombre total de questions à la vue
            'idQuizz' => $idQuizz,
            'breadcrumb' => $breadcrumb
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

    echo json_encode([
        "question" => $question->getContenu(),
        "reponses" => $reponses,
        "difficulty" => $question->getNvDifficulte(),
        "difficultyClass" => $difficultyClass,
        "numero" => $numero,
    ]);
}

    
}







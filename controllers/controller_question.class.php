<?php

class ControllerQuestion extends Controller {
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    // Fonction pour lister toutes les questions d'un quizz
    public function listerQuestion() {
        $idQuizz = isset($_GET['idQuizz']) ? $_GET['idQuizz'] : null;

        // Récupère toutes les questions du quizz
        $managerQuestion = new QuestionDao($this->getPdo());
        $questionListe = $managerQuestion->findAll($idQuizz);

        // Générer la vue
        $template = $this->getTwig()->load('questions.html.twig');
        echo $template->render(['questionListe' => $questionListe]);
    }

    // Fonction pour afficher une question spécifique
    public function afficherQuestion() {
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
    
        // Générer la vue
        $template = $this->getTwig()->load('question.html.twig');
        echo $template->render([
            'question' => $question,
            'reponses' => $reponses,
            'idQuizz' => $idQuizz,
            'numero' => $numero,
            'score' => $_SESSION['score']
        ]);
    }


    // Fonction pour ajouter une question à un quizz
    public function ajouterQuestion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $theme = $_POST['theme'] ?? '';
            $nbQuestion = $_POST['nbQuestion'] ?? 1;
            $difficulte = $_POST['difficulte'] ?? 1;
    
            // Crée un nouveau quizz
            $quizz = new Quizz(null, $nom, $theme, $nbQuestion, $difficulte);
    
            // Sauvegarde dans la base de données
            $managerQuizz = new QuizzDao($this->getPdo());
            if ($managerQuizz->add($quizz)) {
                // Obtenir l'ID du quizz créé
                $idQuizz = $managerQuizz->getLastInsertedId();
    
                // Redirection vers la page d'ajout de questions avec ID du quizz et nbQuestion
                header('Location: index.php?controleur=question&methode=ajouterQuestions&idQuizz=' . $idQuizz . '&nbQuestion=' . $nbQuestion);
                exit;
            } else {
                echo "Erreur lors de l'ajout du quizz.";
            }
        }
    
        // Afficher la vue d'ajout si ce n'est pas une soumission valide
        $template = $this->getTwig()->load('questionAjouter.html.twig');
        echo $template->render();
    }
    
    
    public function saveQuestions() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idQuizz = $_POST['idQuizz'];
            $questions = $_POST['questions']; // Toutes les questions envoyées depuis le formulaire
            
            $questionDao = new QuestionDao();
    
            foreach ($questions as $key => $questionData) {
                $question = new Question(
                    null, // L'ID sera généré automatiquement par la base de données
                    $questionData['contenu'],
                    $key, // Numéro basé sur l'index de la boucle
                    $questionData['nvDifficulte'],
                    $questionData['bonneReponse'],
                    $questionData['cheminImage'] ?? null,
                    $questionData['mauvaiseReponse1'],
                    $questionData['mauvaiseReponse2'],
                    $questionData['mauvaiseReponse3']
                );
    
                $success = $questionDao->add($question);
    
                if (!$success) {
                    // En cas d'erreur, afficher un message ou loguer
                    echo "Erreur lors de l'ajout de la question numéro $key.";
                }
            }
    
            // Redirection après l'enregistrement
            header('Location: index.php?controleur=quizz&methode=listerQuizz');
            exit;
        }
    }
    

    // Fonction pour modifier une question
    public function modifierQuestion() {
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
            $bonneReponse = $_POST['cheminImage'] ?? $question->getcheminImage();
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
    public function supprimerQuestion() {
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
    public function afficherScore() {
        // Récupère le score de la session
        $score = $_SESSION['score'] ?? 0;
    
        // Récupère l'ID du quizz
        $idQuizz = isset($_GET['idQuizz']) ? (int)$_GET['idQuizz'] : null;
    
        // Générer la vue pour afficher le score
        $template = $this->getTwig()->load('quizzResultat.html.twig');
        echo $template->render([
            'score' => $score,
            'idQuizz' => $idQuizz
        ]);
    
        // Réinitialiser le score pour un futur quizz
        unset($_SESSION['score']);
    }
}



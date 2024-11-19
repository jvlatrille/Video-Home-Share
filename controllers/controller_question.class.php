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
        $template = $this->getTwig()->load('questions_list.html.twig');
        echo $template->render(['questionListe' => $questionListe]);
    }

    // Fonction pour afficher une question spécifique
    public function afficherQuestion() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        // Récupère la question
        $managerQuestion = new QuestionDao($this->getPdo());
        $question = $managerQuestion->find($id);

        // Générer la vue
        $template = $this->getTwig()->load('uneQuestion.html.twig');
        echo $template->render([
            'question' => $question,
            'mauvaiseReponse1' => $question->getMauvaiseReponse1(),
            'mauvaiseReponse2' => $question->getMauvaiseReponse2(),
            'mauvaiseReponse3' => $question->getMauvaiseReponse3(),
        ]);
    }

    // Fonction pour ajouter une question à un quizz
    public function ajouterQuestion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupère les données du formulaire
            $contenu = $_POST['contenu'] ?? '';
            $numero = $_POST['numero'] ?? 1;
            $nvDifficulte = $_POST['nvDifficulte'] ?? 1;
            $bonneReponse = $_POST['bonneReponse'] ?? '';
            $cheminImage = $_POST['cheminImage'] ?? '';
            $mauvaiseReponse1 = $_POST['mauvaiseReponse1'] ?? '';
            $mauvaiseReponse2 = $_POST['mauvaiseReponse2'] ?? '';
            $mauvaiseReponse3 = $_POST['mauvaiseReponse3'] ?? '';
            $idQuizz = $_POST['idQuizz'] ?? null;

            // Crée une nouvelle question
            $question = new Question(
                null, 
                $contenu, 
                $numero, 
                $nvDifficulte, 
                $bonneReponse, 
                $mauvaiseReponse1, 
                $mauvaiseReponse2, 
                $mauvaiseReponse3
            );

            // Ajoute la question dans la base de données
            $managerQuestion = new QuestionDao($this->getPdo());
            if ($managerQuestion->add($question, $idQuizz)) {
                // Redirige vers la liste des questions du quizz
                header('Location: index.php?controller=question&action=listerQuestion&idQuizz=' . $idQuizz);
                exit;
            } else {
                // Erreur d'ajout
                echo "Erreur lors de l'ajout de la question.";
            }
        }

        // Générer la vue
        $template = $this->getTwig()->load('ajouter_question.html.twig');
        echo $template->render();
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
                header('Location: index.php?controller=question&action=listerQuestion&idQuizz=' . $question->getIdQuizz());
                exit;
            } else {
                // Erreur de mise à jour
                echo "Erreur lors de la modification de la question.";
            }
        }

        // Générer la vue
        $template = $this->getTwig()->load('modifier_question.html.twig');
        echo $template->render(['question' => $question]);
    }

    // Fonction pour supprimer une question
    public function supprimerQuestion() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        // Supprime la question
        $managerQuestion = new QuestionDao($this->getPdo());
        if ($managerQuestion->delete($id)) {
            // Redirige vers la liste des questions
            header('Location: index.php?controller=question&action=listerQuestion&idQuizz=' . $_GET['idQuizz']);
            exit;
        } else {
            // Erreur de suppression
            echo "Erreur lors de la suppression de la question.";
        }
    }
}

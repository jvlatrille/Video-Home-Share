<?php

class ControllerQuizz extends Controller {
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    // Fonction pour lister tous les quizz
    public function listerQuizz() {
        if (isset($_SESSION['utilisateur'])) {
            // Récupère tous les quizz
            $managerQuizz = new QuizzDao($this->getPdo());
            $quizzListe = $managerQuizz->findAll();
            // Générer la vue
            $template = $this->getTwig()->load('quizzListe.html.twig');
            
            echo $template->render(['quizzListe' => $quizzListe]);
        } else {
            // Redirige vers la page de connexion
            header('Location: index.php?controleur=utilisateur&methode=connexion');
        }
    }

    // Fonction pour afficher un quizz spécifique
    public function afficherQuizz() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        
        // Récupère le quizz
        $managerQuizz = new QuizzDao($this->getPdo());
        $quizz = $managerQuizz->find($id);

        // Générer la vue
        $template = $this->getTwig()->load('quizz.html.twig');
        
        echo $template->render(['quizz' => $quizz]);
    }

    // Fonction pour ajouter un nouveau quizz
    public function ajouterQuizz() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $theme = $_POST['theme'] ?? '';
            $nbQuestion = $_POST['nbQuestion'] ?? 1;
            $difficulte = $_POST['difficulte'] ?? 1;
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            $idCreateur = $utilisateurConnecte->getIdUtilisateur();
        
            $quizz = new Quizz(null, $nom, $theme, $nbQuestion, $difficulte, $idCreateur);
        
            $managerQuizz = new QuizzDao($this->getPdo());
            $idQuizz = $managerQuizz->add($quizz);
        
            if ($idQuizz) {
                // Rediriger avec l'ID du quizz et son nombre de questions
                header('Location: index.php?controleur=question&methode=ajouterQuestions&idQuizz=' . $idQuizz . '&nbQuestion=' . $nbQuestion);
                exit;
            } else {
                echo "Erreur lors de l'ajout du quizz.";
            }
        }
        
        $template = $this->getTwig()->load('quizzAjouter.html.twig');
        echo $template->render();
    }
    
    

    // Fonction pour modifier un quizz
    public function modifierQuizz() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        
        // Récupère le quizz
        $managerQuizz = new QuizzDao($this->getPdo());
        $quizz = $managerQuizz->find($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupère les données du formulaire
            $nom = $_POST['nom'] ?? $quizz->getNom();
            $theme = $_POST['theme'] ?? $quizz->getTheme();
            $nbQuestion = $_POST['nbQuestion'] ?? $quizz->getNbQuestion();
            $difficulte = $_POST['difficulte'] ?? $quizz->getDifficulte();
            $meilleurJ = $_POST['meilleurJ'] ?? $quizz->getMeilleurJ();
            
            // Met à jour l'objet Quizz
            $quizz->setNom($nom);
            $quizz->setTheme($theme);
            $quizz->setNbQuestion($nbQuestion);
            $quizz->setDifficulte($difficulte);
            $quizz->setMeilleurJ($meilleurJ);

            // Met à jour le quizz dans la base de données
            if ($managerQuizz->update($quizz)) {
                // Redirige vers la liste des quizz
                header('Location: index.php?controller=quizz&action=listerQuizz');
                exit;
            } else {
                // Erreur de mise à jour
                echo "Erreur lors de la modification du quizz.";
            }
        }

        // Générer la vue
        $template = $this->getTwig()->load('quizzModifier.html.twig');
        echo $template->render(['quizz' => $quizz]);
    }

    // Fonction pour supprimer un quizz
    public function supprimerQuizz() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        
        // Supprime le quizz
        $managerQuizz = new QuizzDao($this->getPdo());
        if ($managerQuizz->delete($id)) {
            // Redirige vers la liste des quizz
            header('Location: index.php?controller=quizz&action=listerQuizz');
            exit;
        } else {
            // Erreur de suppression
            echo "Erreur lors de la suppression du quizz.";
        }
    }
    
}

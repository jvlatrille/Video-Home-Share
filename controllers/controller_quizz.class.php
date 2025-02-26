<?php

class ControllerQuizz extends Controller {
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    // Fonction pour lister tous les quizz
    public function listerQuizz() {
        if (isset($_SESSION['utilisateur'])) {

            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);

            // Récupère tous les quizz
            $managerQuizz = new QuizzDao($this->getPdo());
            $quizzListe = $managerQuizz->findAll();
            
            // Générer la vue
            $template = $this->getTwig()->load('quizzListe.html.twig');
            
            echo $template->render(['quizzListe' => $quizzListe]);
        }
        else {
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
        $regles = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $theme = $_POST['theme'] ?? '';
            $nbQuestion = $_POST['nbQuestion'] ?? 1;
            $difficulte = $_POST['difficulte'] ?? 1;
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            $idCreateur = $utilisateurConnecte->getIdUtilisateur();
            
            $pseudo = $utilisateurConnecte->getPseudo();
        
            $quizz = new Quizz(null, $nom, $theme, $nbQuestion, $difficulte, $idCreateur, $pseudo, "default.png");

            $image = "default.png";
        
            $managerQuizz = new QuizzDao($this->getPdo());
            $idQuizz = $managerQuizz->add($quizz);
            $messages = [];

            // Vérifier si un fichier a été envoyé
            if (isset($_FILES['imageQuizz']) && $_FILES['imageQuizz']['error'] == 0) {
                // Valider le fichier photo
                $validator = new Validator($regles);
                $photoValide = $validator->validerUploadEtPhoto($_FILES['imageQuizz'], $messages);
                
                // Si la photo est valide
                if ($photoValide) {
                    // Définir le dossier de destination
                    $fileExtension = strtolower(pathinfo($_FILES['imageQuizz']['name'], PATHINFO_EXTENSION));
                    $uploadDir = 'img/quizz/';
                    $fileName = "$idQuizz" . "_" . "$nom" . ".$fileExtension";
                    $filePath = $uploadDir . $fileName;
                    
                    // Supprimer l'ancienne photo si elle existe
                    $anciennePhoto = glob($uploadDir . "$idQuizz" . "_*.{jpg,jpeg,png,gif}", GLOB_BRACE);
                    foreach ($anciennePhoto as $fichier) {
                        if (is_file($fichier)) {
                            unlink($fichier);
                        }
                    }
                    
                    // Déplacer le fichier téléchargé
                    if (!move_uploaded_file($_FILES['imageQuizz']['tmp_name'], $filePath)) {
                        $messages[] = "Erreur lors de l'upload de l'image";
                    }

                    $reussite = $managerQuizz->ajoutImage($idQuizz, $fileName);
                }
            }
        
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
    public function modifierQuizz()
    {
        // Vérifie si les données ont été envoyées via POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération des données du formulaire
            $idQuizz = $_POST['id'];  // L'id du quiz à modifier
            $nom = $_POST['nom'];  // Le nom du quiz
            $theme = $_POST['theme'];  // Le thème du quiz
            $nbQuestion = $_POST['nbQuestion'];  // Le nombre de questions
            $difficulte = $_POST['difficulte'];  // La difficulté du quiz
            
            // Récupération de l'image (si elle a été modifiée)
            $image = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Image a été téléchargée, on la traite
                $image = $this->uploadImage($_FILES['image']);  // Méthode pour gérer le téléchargement de l'image
            }
    
            // Créer une instance de l'objet Quiz et mettre à jour ses propriétés
            $quiz = new Quiz();
            $quiz->setIdQuizz($idQuizz);
            $quiz->setNom($nom);
            $quiz->setTheme($theme);
            $quiz->setNbQuestion($nbQuestion);
            $quiz->setDifficulte($difficulte);
            if ($image) {
                $quiz->setImage($image);  // Met à jour l'image si elle a été modifiée
            }
    
            // Utilisation du DAO (Data Access Object) pour mettre à jour les données dans la base
            $managerQuiz = new QuizDao($this->getPdo());
            $managerQuiz->update($quiz);  // Appel à une méthode update dans le DAO
    
            // Redirige l'utilisateur vers la page de liste des quiz
            header('Location: index.php?controleur=quizz&methode=listerQuizz');
            exit();
        } else {
            // Si la méthode n'est pas POST, redirige ou gère une erreur
            header('Location: index.php?controleur=quizz&methode=listerQuizz');
            exit();
        }
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
    
    public function afficherModif()
    {
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            $this->getTwig()->addGlobal('utilisateurConnecte', $utilisateurConnecte);

            $managerQuizz = new QuizzDao($this->getPdo());
            $quizzListe = $managerQuizz->findId($utilisateurConnecte->getIdUtilisateur());
            
            $template = $this->getTwig()->load('quizzModifier.html.twig');
            echo $template->render(['quizzListe' => $quizzListe]);
        }
    }
}

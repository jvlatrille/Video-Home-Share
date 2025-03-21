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
            
            $breadcrumb = [
                ['title' => 'Accueil', 'url' => 'index.php'],
                ['title' => 'Liste des quiz', 'url' => 'index.php?controleur=Quizz&methode=listerQuizz']
            ];


            // Générer la vue
            $template = $this->getTwig()->load('quizzListe.html.twig');
            
            echo $template->render(['quizzListe' => $quizzListe, 'breadcrumb' => $breadcrumb]);
        }
        else {
            // Redirige vers la page de connexion
            header('Location: index.php?controleur=utilisateur&methode=connexion');
        }
}


    // Fonction pour afficher un quiz spécifique
    public function afficherQuizz() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        
        // Récupère le quizz
        $managerQuizz = new QuizzDao($this->getPdo());
        $quizz = $managerQuizz->find($id);

        $breadcrumb = [
            ['title' => 'Accueil', 'url' => 'index.php'],
            ['title' => 'Liste des quiz', 'url' => 'index.php?controleur=Quizz&methode=listerQuizz'],
            ['title' => $quizz->getNom(), 'url' => 'index.php?controleur=Quizz&methode=afficherQuizz&id=' . $id]
        ];

        // Générer la vue
        $template = $this->getTwig()->load('quizz.html.twig');
        
        echo $template->render(['quizz' => $quizz, 'breadcrumb' => $breadcrumb]);
    }

    // Fonction pour ajouter un nouveau quizz
    public function ajouterQuizz() {    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $theme = $_POST['theme'] ?? '';
            $nbQuestion = $_POST['nbQuestion'] ?? 1;
            $difficulte = $_POST['difficulte'] ?? 1;
            $image = 'default.png';

            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            $idCreateur = $utilisateurConnecte->getIdUtilisateur();
            
            $pseudo = $utilisateurConnecte->getPseudo();
    
            // Créer le nouvel objet Quizz
            $quizz = new Quizz(null, $nom, $theme, $nbQuestion, $difficulte, $idCreateur, $pseudo, $image);
    
            // Ajouter le quizz via le manager
            $managerQuizz = new QuizzDao($this->getPdo());
            $idQuizz = $managerQuizz->add($quizz);
    
            // Appel à uploadImage pour gérer l'upload de l'image
            $info = [$idQuizz, $nom];
            $image = $this->uploadImage($_FILES['imageQuizz'], $info);
            if ($image) {
                $quizz->setImage($image);
                $managerQuizz->update($quizz);
            }
    
            if ($idQuizz) {
                // Rediriger avec l'ID du quizz et son nombre de questions
                header('Location: index.php?controleur=question&methode=ajouterQuestions&idQuizz=' . $idQuizz . '&nbQuestion=' . $nbQuestion);
                exit;
            } else {
                echo "Erreur lors de l'ajout du quizz.";
            }
        }

        $breadcrumb = [
            ['title' => 'Accueil', 'url' => 'index.php'],
            ['title' => 'Liste des quiz', 'url' => 'index.php?controleur=Quizz&methode=listerQuizz'],
            ['title' => 'Ajouter un quiz', 'url' => 'index.php?controleur=Quizz&methode=ajouterQuizz']
        ];
        
        $template = $this->getTwig()->load('quizzAjouter.html.twig');
        echo $template->render(['breadcrumb' => $breadcrumb]);
    }  

    public function uploadImage($image, $info){
        $messages = [];
        $regles = [];
        $idQuizz = $info[0];
        $nom = $info[1];
        // Valider le fichier photo
        $validator = new Validator($regles);
        $photoValide = $validator->validerUploadEtPhoto($image, $messages);
        
        // Si la photo est valide
        if ($photoValide) {
            // Définir le dossier de destination
            $fileExtension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
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
            if (!move_uploaded_file($image['tmp_name'], $filePath)) {
                $messages[] = "Erreur lors de l'upload de l'image";
            }
    
            $managerQuizz = new QuizzDao($this->getPdo());
            $managerQuizz->ajoutImage($idQuizz, $fileName);
            return $fileName;
        }
        return $photoValide;
    }

    // Fonction pour modifier un quizz
    public function modifierQuizz() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        
        // Récupère le quizz
        $managerQuizz = new QuizzDao($this->getPdo());
        $quizz = $managerQuizz->find($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Récupération des données du formulaire
            $idQuizz = $_POST['id'];
            $nom = $_POST['nom'];
            $theme = $_POST['theme'];
            $nbQuestion = $_POST['nbQuestion'];
            $difficulte = $_POST['difficulte'];
            $info = [$idQuizz, $nom];
            
            $managerQuiz = new QuizzDao($this->getPdo());
            $quizExistant = $managerQuiz->find($idQuizz);
            
            if (!$quizExistant) {
                header('Location: index.php?controleur=quizz&methode=listerQuizz');
                exit();
            }
            
            $image = $quizExistant->getImage(); // Garde l'image actuelle par défaut
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image = $this->uploadImage($_FILES['image'], $info);
            }
            
            $quiz = new Quizz($idQuizz, $nom, $theme, $nbQuestion, $difficulte, null, null, $image);
            
            $managerQuiz->update($quiz);
            
            header('Location: index.php?controleur=question&methode=afficherModifierQuestion&id=' . $idQuizz);
            exit();
        } else {
            header('Location: index.php?controleur=quizz&methode=listerQuizz');
            exit();
        }
    }        

    // Fonction pour supprimer un quizz
    public function supprimerQuizz() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        
        // Supprime le quizz
        $managerQuizz = new QuizzDao($this->getPdo());

        $uploadDir = 'img/quizz/';
        $image = glob($uploadDir . "$id" . "_*.{jpg,jpeg,png,gif}", GLOB_BRACE);
        foreach ($image as $fichier) {
            if (is_file($fichier)) {
                unlink($fichier);
            }
        }
        if ($managerQuizz->delete($id)) {
            // Redirige vers la liste des quizz
            header('Location: index.php?controleur=quizz&methode=listerQuizz');
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

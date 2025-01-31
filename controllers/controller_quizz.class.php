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
            $template = $this->getTwig()->load('listQuizzs.html.twig');
            
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $theme = $_POST['theme'] ?? '';
            $nbQuestion = $_POST['nbQuestion'] ?? 1;
            $difficulte = $_POST['difficulte'] ?? 1;
        
            $quizz = new Quizz(null, $nom, $theme, $nbQuestion, $difficulte, null);
        
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
                    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $filePath)) {
                        $messages[] = "Erreur lors de l'upload de l'image";
                    }

                    $managerQuizz->ajoutImage($idQuizz, $fileName);
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

    /**
     * @brief Permet de charger l'image du quizz
     * @author Noah LÉVAL 
     *
     * @return void
     */
    public function chargerImage()
    {
        if (isset($_SESSION['utilisateur'])) {
            $regles = [];
    
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            $this->getTwig()->addGlobal('utilisateurConnecte', $utilisateurConnecte);
    
            $userId = $utilisateurConnecte->getIdUtilisateur();
            $userPseudo = $utilisateurConnecte->getPseudo();
            $messages = [];
            $managerUtilisateur = new UtilisateurDao($this->getPdo());
            
            // Vérifier si un fichier a été envoyé
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
                // Valider le fichier photo
                $validator = new Validator($regles);
                $photoValide = $validator->validerUploadEtPhoto($_FILES['photo'], $messages);
                
                // Si la photo est valide
                if ($photoValide) {
                    // Définir le dossier de destination
                    $fileExtension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                    $uploadDir = 'img/profils/';
                    $fileName = "$userId" . "_" . "$userPseudo" . ".$fileExtension";
                    $filePath = $uploadDir . $fileName;
                    
                    // Supprimer l'ancienne photo si elle existe
                    $anciennePhoto = glob($uploadDir . "$userId" . "_*.{jpg,jpeg,png,gif}", GLOB_BRACE);
                    foreach ($anciennePhoto as $fichier) {
                        if (is_file($fichier)) {
                            unlink($fichier);
                        }
                    }
                    
                    // Déplacer le fichier téléchargé
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $filePath)) {
                        // Mettre à jour la photo de profil dans la base de données
                        $userId = $_POST['utilisateurId']; // Récupérer l'ID utilisateur depuis le formulaire
                        $reussite = $managerUtilisateur->updateUserPhoto($userId, $fileName);
                        
                        if ($reussite) {
                            $messages[] = "La photo de profil a été mise à jour avec succès.";
                        } else {
                            $messages[] = "Erreur lors de la mise à jour de la photo de profil dans la base de données.";
                        }
                    } else {
                        $messages[] = "Erreur lors du téléchargement du fichier.";
                    }
                } else {
                    $messages[] = "La photo de profil n'est pas valide.";
                }
            } else {
                $messages[] = "Aucune photo téléchargée ou erreur lors du téléchargement.";
            }
    
            $utilisateur = $managerUtilisateur->find($userId);
        
            // Mise à jour de la session avec les nouvelles données
            $_SESSION['utilisateur'] = serialize($utilisateur);
        
            header('Location: index.php?controleur=profil&methode=afficherFormulaire');
        }
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

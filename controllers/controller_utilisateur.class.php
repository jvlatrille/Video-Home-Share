<?php

class ControllerUtilisateur extends Controller
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    // Afficher tous les utilisateurs
    public function AllUtilisateurs()
    {
        // Récupère tout les utilisateurs
        $managerUtilisateur = new UtilisateurDAO($this->getPdo());
        $utilisateurListe = $managerUtilisateur->findAll();

        // Génère la vue
        $template = $this->getTwig()->load('utilisateur.html.twig');
        echo $template->render(['utilisateurListe' => $utilisateurListe]);
    }

    public function afficherUtilisateur()
{
    // Vérifie si un utilisateur est connecté
    if (isset($_SESSION['utilisateur'])) {
        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        //$this->getTwig()->addGlobal('utilisateurConnecte', $utilisateurConnecte);

        // Récupère l'utilisateur
        $template = $this->getTwig()->load('profilNotifications.html.twig');
        echo $template->render([
            'utilisateur' => $utilisateurConnecte
        ]);
        return;
    }

    // Sinon, affiche la page de connexion
    $template = $this->getTwig()->load('connexion.html.twig');
    echo $template->render();
}


    // Changer de pseudo
    public function changerPseudo() {
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            $this->getTwig()->addGlobal('utilisateurConnecte', $utilisateurConnecte);

            $id = $utilisateurConnecte->getIdUtilisateur();
            $newPseudo = isset($_GET['pseudo']) ? $_GET['pseudo'] : null;
    
        if (!$id || !$newPseudo) {
            throw new Exception('Informations manquantes : ID ou pseudo.');
        }
        
        $managerUtilisateur = new UtilisateurDao($this->getPdo());
        $reussite = $managerUtilisateur->changerPseudo($id, $newPseudo);
    
        $message = $reussite ? "Le pseudo a été changé avec succès." : "Erreur lors du changement de pseudo.";
        $utilisateur = $managerUtilisateur->find($id);
    
        $template = $this->getTwig()->load('profilParametres.html.twig');
        echo $template->render([
            'utilisateur' => $utilisateur,
            'message' => $message
        ]);
    }    
}
    // Changer de Mail
    public function changerMail() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $newMail = isset($_GET['mail']) ? $_GET['mail'] : null;
    
        if (!$id || !$newMail) {
            throw new Exception('Informations manquantes : ID ou mail.');
        }
        
        $managerUtilisateur = new UtilisateurDao($this->getPdo());
        $reussite = $managerUtilisateur->changerMail($id, $newMail);
    
        $message = $reussite ? "Le mail a été changé avec succès." : "Erreur lors du changement de mail.";
        $utilisateur = $managerUtilisateur->find($id);
    
        $template = $this->getTwig()->load('profilParametres.html.twig');
        echo $template->render([
            'utilisateur' => $utilisateur,
            'message' => $message
        ]);
    }

    public function changerPhotoProfil()
    {
        // Vérifier si un fichier a été envoyé
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {

            // Récupérer l'ID de l'utilisateur depuis la requête
            $userId = $_POST['userId'];

            // Récupérer les informations du fichier téléchargé
            $fileTmpName = $_FILES['photo']['tmp_name'];
            $fileName = basename($_FILES['photo']['name']);

            // Définir le dossier cible où l'image sera enregistrée
            $targetDir = "img/profils/"; // Dossier relatif à l'arborescence de votre projet
            $targetFile = $targetDir . $fileName;
            
            // Vérifier que l'extension du fichier est autorisée
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (!in_array($fileExtension, $allowedExtensions)) {
                echo json_encode(["success" => false, "message" => "Extension de fichier non autorisée."]);
                return;
            }

            // Déplacer le fichier téléchargé vers le dossier de destination
            if (move_uploaded_file($fileTmpName, $targetFile)) {
                // Appeler la méthode pour mettre à jour la photo de profil dans la base de données
                $managerUtilisateur = new UtilisateurDao($this->getPdo());
                $updateSuccess = $managerUtilisateur->updateUserPhoto($userId, $fileName);

                if ($updateSuccess) {
                    $message = "La photo de profil a été changée avec succès.";
                } else {
                    $message = "Erreur lors du changement de photo de profil.";
                }

                // Récupérer l'utilisateur mis à jour pour l'afficher dans le template
                $utilisateur = $managerUtilisateur->find($userId);

                // Charger et rendre le template Twig
                $template = $this->getTwig()->load('profilParametres.html.twig');
                echo $template->render([
                    'utilisateur' => $utilisateur,
                    'message' => $message
                ]);
            } else {
                echo json_encode(["success" => false, "message" => "Erreur lors de l'upload de la photo."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Aucun fichier n'a été téléchargé."]);
        }
    }

    public function changerBanniere()
    {
        // Vérifier si un fichier a été téléchargé
        if (isset($_FILES['banniere']) && $_FILES['banniere']['error'] == 0) {
            $userId = $_POST['userId'];

            // Récupérer les informations du fichier téléchargé
            $fileTmpName = $_FILES['banniere']['tmp_name'];
            $fileName = basename($_FILES['banniere']['name']);

            // Définir le dossier de destination
            $targetDir = "img/banniere/"; // Le dossier des bannières
            $targetFile = $targetDir . $fileName;

            // Vérifier l'extension du fichier
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (!in_array($fileExtension, $allowedExtensions)) {
                echo json_encode(["success" => false, "message" => "Extension de fichier non autorisée."]);
                return;
            }

            // Déplacer le fichier téléchargé
            if (move_uploaded_file($fileTmpName, $targetFile)) {
                // Appeler la méthode pour mettre à jour la bannière dans la base de données
                $managerUtilisateur = new UtilisateurDao($this->getPdo());
                $updateSuccess = $managerUtilisateur->updateUserBanniere($userId, $fileName);

                if ($updateSuccess) {
                    $message = "La bannière a été changée avec succès.";
                } else {
                    $message = "Erreur lors du changement de la bannière.";
                }

                // Récupérer l'utilisateur mis à jour pour l'afficher
                $utilisateur = $managerUtilisateur->find($userId);

                // Charger et rendre le template
                $template = $this->getTwig()->load('profilParametres.html.twig');
                echo $template->render([
                    'utilisateur' => $utilisateur,
                    'message' => $message
                ]);
            } else {
                echo json_encode(["success" => false, "message" => "Erreur lors de l'upload de la bannière."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Aucun fichier n'a été téléchargé."]);
        }
    }
 

    
    /**
     * @brief Affiche le formulaire de connexion d'un utilisateur
     * @author Thibault CHIPY 
     *
     * @return void
     */
    public function connexion(){
        $template = $this->getTwig()->load('connexion.html.twig');
        echo $template->render();
    }

    /**
     * @brief Affiche le formulaire d'inscription d'un utilisateur
     * @author Thibault CHIPY 
     * 
     * @return void
     */

    public function inscription(){
        $template = $this->getTwig()->load('inscription.html.twig');
        echo $template->render();
    }

    /**
     * @brief Vérifie la connexion d'un utilisateur
     * @author Thibault Chipy 
     * @version 1.0
     * 
     * @return void
     */
    public function verifConnexion(){
        $mail=isset($_POST['mail'])?$_POST['mail']:null;
        $mdp=isset($_POST['mdp'])?$_POST['mdp']:null;

        // $mail = str_replace(' ', '', $mail); // On enlève les espaces
        $managerUtilisateur = new UtilisateurDao($this->getPdo());
        $utilisateur = $managerUtilisateur->findByMail($mail);
        if($utilisateur && password_verify($mdp, $utilisateur->getMotDePasse())){
            
            $this->afficherUtilisateur();
            $_SESSION['utilisateur'] = serialize($utilisateur);

        }else{  
            $template = $this->getTwig()->load('connexion.html.twig');
            echo $template->render(['message' => 'Identifiants incorrects']);
        }
    }

    /**
     * @brief Vérifie l'inscription d'un utilisateur et l'ajoute à la base de données si tout est correcte
     * @author Thibault CHIPY
     * 
     * @return void
     */

// MANQUE LES VERIF DES FORMULAIRES AVEC FONCTION : A FAIRE      
    public function verifInscription(){
        $idUtilisateur=isset($_POST['idUtilisateur'])?$_POST['idUtilisateur']:null;
        $pseudo=isset($_POST['pseudo'])?$_POST['pseudo']:null;
        $photoProfil=isset($_POST['photoProfil'])?$_POST['photoProfil']:null;
        $banniereProfil=isset($_POST['banniereProfil'])?$_POST['banniereProfil']:null;
        $dateNaiss=isset($_POST['dateNaiss'])?$_POST['dateNaiss']:null;
        $mail=isset($_POST['mail'])?$_POST['mail']:null;
        $mdp=isset($_POST['mdp'])?$_POST['mdp']:null;
        $mdpVerif=isset($_POST['mdpVerif'])?$_POST['mdpVerif']:null;
        $role=isset($_POST['role'])?$_POST['role']:'utilisateur'; // Role par défaut : utilisateur
        // $mail = str_replace(' ', '', $mail); // On enlève les espaces

        
        //Vérifier l'age de l'utilisateur
        $dateJour = date('Y-m-d');
        
        //Comparé la date du jour avec la date de naissance de l'utilisateur
        $dateNaiss = new DateTime($dateNaiss);
        $dateJour = new DateTime($dateJour);
        $age = $dateNaiss->diff($dateJour);
        $age = $age->format('%y');

        $verifPassee=true;

        //Si l'utilisateur a moins de 13 ans
        if($age < 13){
            $template = $this->getTwig()->load('inscription.html.twig');
            echo $template->render(['message' => 'Vous devez avoir au moins
            13 ans pour vous inscrire']);
        }
        

        $managerUtilisateur = new UtilisateurDao($this->getPdo());
        $utilisateur = $managerUtilisateur->emailExiste($mail);
        $verifMdp=$managerUtilisateur->estRobuste($mdp);
        
        // Vérifie si l'email existe déjà
        if($utilisateur){
            $template = $this->getTwig()->load('inscription.html.twig');
            echo $template->render(['message' => 'L\'adresse mail est déjà utilisée']);
            $verifPassee=false;
            return;
        }
        
        if($mdp != $mdpVerif){
            $template = $this->getTwig()->load('inscription.html.twig');
            echo $template->render(['message' => 'Les mots de passe ne correspondent pas']);
            $verifPassee=false;
            return;
        }
        if(!$verifMdp){
            $template = $this->getTwig()->load('inscription.html.twig');
            echo $template->render(['message' => 'Le mot de passe n\'est pas assez robuste']);
            $verifPassee=false;
            return;
        }

        if($verifPassee){
        $mdp = password_hash($mdp, PASSWORD_BCRYPT); // On hash le mot de passe avec BCRYPT
        $utilisateur = new Utilisateur($idUtilisateur,$pseudo, $photoProfil, $banniereProfil, $mail, $mdp, $role); // Role par défaut : utilisateur
        $utilisateur->setIdUtilisateur($this->getPdo()->lastInsertId());
        $managerUtilisateur->creerUtilisateur($utilisateur);
        }
        else{
            $template = $this->getTwig()->load('inscription.html.twig');
            echo $template->render(['message' => 'Erreur lors de l\'inscription']);
        }
        header('Location: index.php?controleur=utilisateur&methode=connexion');
        }

        /**
         * @brief Déconnecte un utilisateur et le redirige vers la page d'accueil
         * @details Détruit la session de l'utilisateur et le redirige vers la page d'accueil
         * 
         * @return void
         */
        public function deconnexion(){
            session_destroy();
            header('Location: index.php');
        }
}

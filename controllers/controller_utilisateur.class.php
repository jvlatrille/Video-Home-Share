<?php

class ControllerUtilisateur extends Controller
{
    private array $reglesValidation;

    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
        $this->reglesValidation = [
            'pseudo' => [
                'obligatoire' => false,
                'type' => 'string',
                'longueur_min' => 5,
                'longueur_max' => 40,
                'format' => '/^[a-zA-ZÀ-ÿ\'-]+$/'
            ],
            'mail' => [
                'obligatoire' => false,
                'type' => 'string',
                'longueur_min' => 5,
                'longueur_max' => 255,
                'format' => FILTER_VALIDATE_EMAIL
            ],
        ];
    }

    public function get_regles(): ?array
    {
        return $this->reglesValidation;
    }

    public function set_regles(array $regle): void
    {
        $this->reglesValidation = regle;
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
        $this->getTwig()->addGlobal('utilisateurConnecte', $utilisateurConnecte);

        // Récupère l'utilisateur
        $template = $this->getTwig()->load('profil.html.twig');
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
        // Récupération des données via POST
        $id = isset($_POST['utilisateurId']) ? intval($_POST['utilisateurId']) : null;
        $newPseudo = isset($_POST['pseudo']) ? trim($_POST['pseudo']) : null;
        $donnees = ["pseudo" => $newPseudo];

        // Vérification des données reçues
        $validator = new Validator($this->get_regles());
        $valides = $validator->valider($donnees);
    
        // Interaction avec le DAO pour mettre à jour le pseudo
        $managerUtilisateur = new UtilisateurDao($this->getPdo());
        if ($valides)
        {
            $reussite = $managerUtilisateur->changerPseudo($id, $newPseudo);
        }
    
        // Génération d'un message en fonction du succès ou de l'échec
        $messages = $validator->getMessagesErreurs();
        $utilisateur = $managerUtilisateur->find($id);

        // Chargement et rendu du template
        $template = $this->getTwig()->load('profilParametres.html.twig');
        echo $template->render([
            'utilisateur' => $utilisateur,
            'message' => $messages
        ]);
    }
     
    
    // Changer de Mail
    public function changerMail() {
        // Récupération des données via POST
        $id = isset($_POST['utilisateurId']) ? intval($_POST['utilisateurId']) : null;
        $newMail = isset($_POST['mail']) ? trim($_POST['mail']) : null;
        $donnees = ["mail" => $newMail];

        // Vérification des données reçues
        $validator = new Validator($this->get_regles());
        $valides = $validator->valider($donnees);
    
        // Interaction avec le DAO pour mettre à jour le pseudo
        $managerUtilisateur = new UtilisateurDao($this->getPdo());
        if ($valides)
        {
            $reussite = $managerUtilisateur->changerMail($id, $newMail);
        }
    
        // Génération d'un message en fonction du succès ou de l'échec
        $messages = $validator->getMessagesErreurs();
        $utilisateur = $managerUtilisateur->find($id);
    
        // Chargement et rendu du template
        $template = $this->getTwig()->load('profilParametres.html.twig');
        echo $template->render([
            'utilisateur' => $utilisateur,
            'message' => $messages
        ]);
    }

    public function changerPhotoProfil()
    {
        $messages = [];
        $managerUtilisateur = new UtilisateurDao($this->getPdo());
        
        // Vérifier si un fichier a été envoyé
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            // Valider le fichier photo
            $validator = new Validator($this->get_regles());
            $photoValide = $validator->validerUploadEtPhoto($_FILES['photo'], $messages);
            
            // Si la photo est valide
            if ($photoValide) {
                // Définir le dossier de destination
                $uploadDir = 'img/profils/';
                $fileName = basename($_FILES['photo']['name']); //time() . '_' . basename($_FILES['photo']['name']);
                $filePath = $uploadDir . $fileName;
                
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
    
        // Chargement et rendu du template
        $utilisateur = $managerUtilisateur->find($_POST['utilisateurId']);
        $template = $this->getTwig()->load('profilParametres.html.twig');
        echo $template->render([
            'utilisateur' => $utilisateur,
            'messages' => $messages
        ]);
    }
    

    public function changerBanniere()
    {
        $messages = [];
        $managerUtilisateur = new UtilisateurDao($this->getPdo());
        
        // Vérifier si un fichier a été envoyé
        if (isset($_FILES['banniere']) && $_FILES['banniere']['error'] == 0) {
            // Valider le fichier photo
            $validator = new Validator($this->get_regles());
            $photoValide = $validator->validerUploadEtPhoto($_FILES['banniere'], $messages);
            
            // Si la photo est valide
            if ($photoValide) {
                // Définir le dossier de destination
                $uploadDir = 'img/banniere/';
                $fileName = basename($_FILES['banniere']['name']); //time() . '_' . basename($_FILES['banniere']['name']);
                $filePath = $uploadDir . $fileName;
                
                // Déplacer le fichier téléchargé
                if (move_uploaded_file($_FILES['banniere']['tmp_name'], $filePath)) {
                    // Mettre à jour la banniere dans la base de données
                    $userId = $_POST['utilisateurId']; // Récupérer l'ID utilisateur depuis le formulaire
                    $reussite = $managerUtilisateur->updateUserBanniere($userId, $fileName);
                    
                    if ($reussite) {
                        $messages[] = "La banniere a été mise à jour avec succès.";
                    } else {
                        $messages[] = "Erreur lors de la mise à jour de la banniere dans la base de données.";
                    }
                } else {
                    $messages[] = "Erreur lors du téléchargement du fichier.";
                }
            } else {
                $messages[] = "La banniere n'est pas valide.";
            }
        } else {
            $messages[] = "Aucune photo téléchargée ou erreur lors du téléchargement.";
        }
    
        // Chargement et rendu du template
        $utilisateur = $managerUtilisateur->find($_POST['utilisateurId']);
        $template = $this->getTwig()->load('profilParametres.html.twig');
        echo $template->render([
            'utilisateur' => $utilisateur,
            'messages' => $messages
        ]);
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

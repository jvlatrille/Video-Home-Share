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

        $template = $this->getTwig()->load('profil.html.twig');
        echo $template->render(['utilisateur' => $utilisateurConnecte]);
        return; // Arrête l'exécution de la méthode sinon on a un double affichage
    }

    // Sinon, affiche la page de connexion
    $template = $this->getTwig()->load('connexion.html.twig');
    echo $template->render();
}

public function afficherAutreUtilisateur()
{
    // Vérifie si un utilisateur est connecté
    if (isset($_SESSION['utilisateur'])) {
        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);

        $pseudoUtilisateur = isset($_GET['pseudo']) ? $_GET['pseudo'] : null;
        $managerUtilisateur = new UtilisateurDao($this->getPdo());
        $autreUtilisateur = $managerUtilisateur->findByPseudo($pseudoUtilisateur);
        $template = $this->getTwig()->load('profilAutre.html.twig');
        echo $template->render(['utilisateur' => $autreUtilisateur]);
        return; // Arrête l'exécution de la méthode sinon on a un double affichage
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
     * @version 2.0
     * 
     * @return void
     */
    public function verifConnexion(){
        // Récupération des données du formulaire
        $donneesFormulaire = [
            'mail' => htmlspecialchars($_POST['mail'],ENT_QUOTES,) ?? null,
            'mdp' => htmlspecialchars($_POST['mdp'],ENT_QUOTES) ?? null,
        ];
        
        // Validation des données
       $erreurs = Validator::validerConnexion($donneesFormulaire);
        if($erreurs){
            $template = $this->getTwig()->load('connexion.html.twig');
            echo $template->render(['erreurs' => $erreurs]);
            return;
        }

        $mail = $donneesFormulaire['mail'];
        $mdp = $donneesFormulaire['mdp'];
        $managerUtilisateur = new UtilisateurDao($this->getPdo());
        $utilisateur = $managerUtilisateur->findByMail($mail);
        if($utilisateur && password_verify($mdp, $utilisateur->getMotDePasse())){

            $_SESSION['utilisateur'] = serialize($utilisateur);
            $this->getTwig()->addGlobal('utilisateurConnecte', $utilisateur);
            
            $managerOA = new OADao($this->getPdo());
            $oaListe = $managerOA->findMeilleurNote();
            $template = $this->getTwig()->load('index.html.twig');
            echo $template->render(['oaListe' => $oaListe]);
            
        }
        else{
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

    public function verifInscription()
    {
        // Récupération des données du formulaire
        $donneesFormulaire = [

            'idUtilisateur' => htmlspecialchars($_POST['idUtilisateur'] ?? null, ENT_QUOTES),
            'pseudo' => htmlspecialchars($_POST['pseudo'] ?? null, ENT_QUOTES),
            'photoProfil' => htmlspecialchars($_POST['photoProfil'] ?? 'default.png', ENT_QUOTES), // Image par défaut
            'banniereProfil' => htmlspecialchars($_POST['banniereProfil'] ?? "default.png", ENT_QUOTES), // Image par défaut
            'dateNaiss' => htmlspecialchars($_POST['dateNaiss'] ?? null, ENT_QUOTES),
            'mail' => htmlspecialchars($_POST['mail'] ?? null, ENT_QUOTES),
            'mdp' => htmlspecialchars($_POST['mdp'] ?? null, ENT_QUOTES),
            'mdpVerif' => htmlspecialchars($_POST['mdpVerif'] ?? null, ENT_QUOTES),
            'role' => htmlspecialchars($_POST['role'] ?? 'utilisateur', ENT_QUOTES), // Role par défaut
            'bio' => htmlspecialchars($_POST['bio'] ?? NULL,ENT_QUOTES),

        ];

        // Définition des règles de validation
        $reglesValidation = [
            'pseudo' => [
                'obligatoire' => true,
                'type' => 'string',
                'longueur_min' => 3,
                'longueur_max' => 50,
            ],
            'dateNaiss' => [
                'obligatoire' => true,
                'type' => 'date',
                'validation_personnalisee' => function ($value) {
                    $dateNaiss = DateTime::createFromFormat('Y-m-d', $value);
                    if (!$dateNaiss) {
                        return 'La date de naissance doit être valide (format YYYY-MM-DD).';
                    }
                    $dateJour = new DateTime();
                    $age = $dateNaiss->diff($dateJour)->y;
                    return $age >= 13 ? true : 'Vous devez avoir au moins 13 ans pour vous inscrire';
                },
            ],

            'mail' => [
                'obligatoire' => true,
                'format' => FILTER_VALIDATE_EMAIL,
            ],
            'mdp' => [
                'obligatoire' => true,
                'validation_personnalisee' => function ($value) {
                    return UtilisateurDao::estRobuste($value) ? true : 'Le mot de passe n\'est pas assez robuste';
                },
            ],
            'mdpVerif' => [
                'obligatoire' => true,
                'validation_personnalisee' => function ($value) use ($donneesFormulaire) {
                    return $value === $donneesFormulaire['mdp'] ? true : 'Les mots de passe ne correspondent pas';
                },
            ],
        ];

        // Validation des données
        $validator = new Validator($reglesValidation);

        if (!$validator->valider($donneesFormulaire)) {
            // Récupération des erreurs
            $erreurs = $validator->getMessagesErreurs();
            // Rendre la vue avec les erreurs
            $template = $this->getTwig()->load('inscription.html.twig');
            echo $template->render(['erreurs' => $erreurs, 'donnees' => $donneesFormulaire]);
            return;
        }

        // Vérifie si l'email existe déjà
        $managerUtilisateur = new UtilisateurDao($this->getPdo());
        if ($managerUtilisateur->emailExiste($donneesFormulaire['mail'])) {
            $template = $this->getTwig()->load('inscription.html.twig');
            echo $template->render(['message' => 'L\'adresse mail est déjà utilisée', 'donnees' => $donneesFormulaire]);
            return;
        }

        // Hachage du mot de passe
        $donneesFormulaire['mdp'] = password_hash($donneesFormulaire['mdp'], PASSWORD_BCRYPT);

        // Création de l'utilisateur
        $utilisateur = new Utilisateur(

            htmlspecialchars_decode($donneesFormulaire['idUtilisateur'], ENT_QUOTES),
            htmlspecialchars_decode($donneesFormulaire['pseudo'], ENT_QUOTES),
            htmlspecialchars_decode($donneesFormulaire['photoProfil'], ENT_QUOTES),
            htmlspecialchars_decode($donneesFormulaire['banniereProfil'], ENT_QUOTES),
            htmlspecialchars_decode($donneesFormulaire['mail'], ENT_QUOTES),
            htmlspecialchars_decode($donneesFormulaire['mdp'], ENT_QUOTES),
            htmlspecialchars_decode($donneesFormulaire['role'], ENT_QUOTES)
            htmlspecialchars_decode($donneesFormulaire['bio'], ENT_QUOTES)

        );

        // Sauvegarde dans la base de données
        $managerUtilisateur->creerUtilisateur($utilisateur);

        // Redirection vers la page de connexion
        header('Location: index.php?controleur=utilisateur&methode=connexion');
        exit;
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

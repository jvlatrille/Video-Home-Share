<?php
// envoie de mail en local
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * @file controller_profil.class.php
 * @author Léval Noah, Thibault Chipy
 * @brief Controleur des utilisateurs
 * @version 2.0
 * @date 14/11/2024
 */

/**
 * @file controller_profil.class.php
 * @author Léval Noah, Thibault Chipy
 * @brief Controleur des utilisateurs
 * @version 2.0
 * @date 14/11/2024
 */

class ControllerUtilisateur extends Controller
{
    /**
     * @brief Constructeur du controler d'utilisateur
     * @param \Twig\Environment $twig Environnement Twig
     * @param \Twig\Loader\FilesystemLoader $loader Loader Twig
     */
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    /**
     * @brief Permet d'afficher tout les utilisateurs
     * @author Noah LÉVAL 
     *
     * @return void
     */
    public function AllUtilisateurs()
    {
        // Récupère tout les utilisateurs
        $managerUtilisateur = new UtilisateurDAO($this->getPdo());
        $utilisateurListe = $managerUtilisateur->findAll();

        // Génère la vue
        $template = $this->getTwig()->load('utilisateur.html.twig');
        echo $template->render(['utilisateurListe' => $utilisateurListe]);
    }

    /**
     * @brief Permet d'afficher un utilisateur
     * @author Noah LÉVAL 
     *
     * @return void
     */
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

    /**
     * @brief Permet d'afficher la page d'un autre utilisateur
     * @author VINET LATRILLE Jules
     *
     * @return void
     */
    public function afficherAutreUtilisateur()
    {
        // Vérifie si un utilisateur est connecté
        if (isset($_SESSION['utilisateur'])) {
            $pseudoUtilisateur = isset($_GET['pseudo']) ? $_GET['pseudo'] : null;

            $managerUtilisateur = new UtilisateurDao($this->getPdo());
            $autreUtilisateur = $managerUtilisateur->findByPseudo($pseudoUtilisateur);

            $managerMessage = new MessageDAO($this->getPdo());
            $messages = $managerMessage->getMessagesByUser($autreUtilisateur->getIdUtilisateur());

            // Grouper les messages par forumNom
            $groupedMessages = [];
            foreach ($messages as $message) {
                $forumNom = $message['forumNom'];
                if (!isset($groupedMessages[$forumNom])) {
                    $groupedMessages[$forumNom] = [];
                }
                $groupedMessages[$forumNom][] = $message;
            }

            $managerCommentaire = new CommentaireDAO($this->getPdo());
            $commentaires = $managerCommentaire->findCommentairesByIdUtilisateur($autreUtilisateur->getIdUtilisateur());

            $breadcrumb = [
                ['title' => 'Accueil', 'url' => 'index.php'],
                ['title' => $autreUtilisateur->getPseudo(), 'url' => 'index.php?controleur=utilisateur&methode=afficherAutreUtilisateur&pseudo=' . $pseudoUtilisateur]
            ];

            $template = $this->getTwig()->load('profilAutre.html.twig');
            echo $template->render(['utilisateur' => $autreUtilisateur, 'groupedMessages' => $groupedMessages, 'commentaires' => $commentaires,'breadcrumb' => $breadcrumb]);
            return; // Arrête l'exécution de la méthode sinon on a un double affichage
        }

        // Sinon, affiche la page de connexion
        $template = $this->getTwig()->load('connexion.html.twig');
        echo $template->render();
    }

    /**
     * @brief Permet de changer le pseudo de l'utilisateur
     * @author Noah LÉVAL 
     *
     * @return void
     */
    public function changerPseudo()
    {
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
    public function changerMail()
    {
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

    /**
     * @brief Permet de changer la photo de profil
     * @author Noah LÉVAL 
     *
     * @return void
     */
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

    /**
     * @brief Permet de changer la banniere du l'utilisateur
     * @author Noah LÉVAL 
     *
     * @return void
     */
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
     * @brief Affiche la page du mot de passe oublie
     * @author Noah LÉVAL 
     *
     * @return void
     */
    public function mdpOublie()
    {
        $breadcrumb = [
            ['title' => 'Accueil', 'url' => 'index.php'],
            ['title' => 'Mot de passe oublié', 'url' => 'index.php?controleur=utilisateur&methode=mdpOublie']
        ];
        $template = $this->getTwig()->load('motDePasseOublie.html.twig');
        echo $template->render(['breadcrumb' => $breadcrumb]);
    }

    /**
     * @brief Envoie le mail de réinitialisation du mot de passe
     * @author Noah LÉVAL 
     *
     * @return void
     */
    public function motDePasseOublie()
    {
        // Vérifie si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            // Valide que l'email a été soumis et qu'il est correct
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['message'] = "Veuillez entrer une adresse email valide.";
                header('Location: index.php?controleur=utilisateur&methode=motDePasseOublie');
                exit();
            }
            $url = $_POST['currentUrl'];

            // Vérifie si l'email existe dans la base de données
            $managerUtilisateur = new UtilisateurDao($this->getPdo());
            $utilisateur = $managerUtilisateur->findByMail($email);

            if ($utilisateur) {
                // Génère un token unique pour la réinitialisation
                $token = bin2hex(random_bytes(32));
                $tokenCrypt = password_hash($token, PASSWORD_BCRYPT);

                $expiresAt = date('Y-m-d H:i:s', time() + 3600); // Token valide pour 1 heure

                // Enregistre le token dans la base de données
                $managerUtilisateur->enregistrerToken($utilisateur->getIdUtilisateur(), $tokenCrypt, $expiresAt);
                $idUtilisateur = $utilisateur->getIdUtilisateur();

                // Encode l'ID et le token pour les passer de manière sécurisée dans l'URL
                $idEncoded = urlencode(base64_encode($idUtilisateur));
                $tokenEncoded = urlencode(base64_encode($token));

                // Crée le lien de réinitialisation
                $parts = explode('~', $url);
                $util = explode('/', $parts[1])[0];
                $lienReset = "http://lakartxela.iutbayonne.univ-pau.fr/~" . $util . "/SAE3.01/Temporairement_VHS/Video-Home-Share/index.php?controleur=utilisateur&methode=pageChangerMDP&id=$idEncoded&token=$tokenEncoded";

                // Envoie un email avec le lien de réinitialisation
                $sujet = "Reinitialisation de votre mot de passe";
                $message = "Bonjour,\n\nCliquez sur le lien ci-dessous pour réinitialiser votre mot de passe :\n$lienReset\n\nSi vous n'avez pas demandé de réinitialisation, ignorez cet email.";
                mail($email, $sujet, $message);

                // Message de succès
                $_SESSION['message'] = "Un email avec un lien de réinitialisation vous a été envoyé si cette adresse est associée à un compte.";
            } else {
                // Message pour ne pas révéler si l'email existe ou non
                $_SESSION['message'] = "Un email avec un lien de réinitialisation vous a été envoyé si cette adresse est associée à un compte.";
            }
            // Redirige l'utilisateur vers la même page
            header('Location: index.php?controleur=utilisateur&methode=mdpOublie');
            exit();
        }
    }

    /**
     * @brief Affiche la page dédié au changement de mot de passe
     *
     * @return void
     */
    public function pageChangerMDP()
    {
        // Récupérer le token depuis l'URL
        $id = base64_decode(urldecode($_GET['id']));
        $token = base64_decode(urldecode($_GET['token']));

        $managerUtilisateur = new UtilisateurDao($this->getPDO());
        $tokenCrypt = $managerUtilisateur->getTokenById($id);

        if (password_verify($token, $tokenCrypt)) {
            // Transmettre le token au template Twig
            $template = $this->getTwig()->load('changerMDP.html.twig');
            echo $template->render([
                'token' => $tokenCrypt // Transmettre le token au formulaire
            ]);
            return Null;
        }
        header('Location: index.php');
        exit();
    }

    /**
     * @brief Change le mot de passe de l'utilisateur aprés modification
     *
     * @return void
     */
    public function changerMdp()
    {
        $mdp = isset($_POST['MDP1']) ? $_POST['MDP1'] : null;
        $mdpVerif = isset($_POST['MDP2']) ? $_POST['MDP2'] : null;
        $token = isset($_POST['token']) ? $_POST['token'] : null;

        $managerUtilisateur = new UtilisateurDao($this->getPdo());
        $idUser = $managerUtilisateur->getIdUserByToken($token);

        if (!$managerUtilisateur->estRobuste($mdp)) {
            $template = $this->getTwig()->load('changerMDP.html.twig');
            echo $template->render([
                'mdpValide' => True,
                'message' => "Le mot de passe n'est pas assez robuste"
            ]);
            return;
        }

        if ($mdp != $mdpVerif) {
            $template = $this->getTwig()->load('changerMDP.html.twig');
            echo $template->render([
                'mdpValide' => True,
                'message' => 'Les mots de passe ne correspondent pas'
            ]);
            return;
        }

        $mdpHash = password_hash($mdp, PASSWORD_BCRYPT);
        $managerUtilisateur->changerMdp($idUser, $mdpHash);
        $managerUtilisateur->supprimerToken($token);

        // Mise à jour de la session avec les nouvelles données
        header('Location: index.php?controleur=utilisateur&methode=connexion');
    }

    /**
     * @brief Affiche le formulaire de connexion d'un utilisateur
     * @author Thibault CHIPY 
     *
     * @return void
     */
    public function connexion()
    {
        $breadcrumb = [
            ['title' => 'Accueil', 'url' => 'index.php'],
            ['title' => 'Connexion', 'url' => 'index.php?controleur=utilisateur&methode=connexion']
        ];
        $template = $this->getTwig()->load('connexion.html.twig');
        echo $template->render(['breadcrumb' => $breadcrumb]);
    }

    /**
     * @brief Affiche le formulaire d'inscription d'un utilisateur
     * @author Thibault CHIPY 
     * 
     * @return void
     */

    public function inscription()
    {
        $breadcrumb = [
            ['title' => 'Accueil', 'url' => 'index.php'],
            ['title' => 'Inscription', 'url' => 'index.php?controleur=utilisateur&methode=inscription']
        ];
        $template = $this->getTwig()->load('inscription.html.twig');
        echo $template->render(['breadcrumb' => $breadcrumb]);
    }

    /**
     * @brief Vérifie la connexion d'un utilisateur
     * @author Thibault Chipy 
     * @version 2.0
     * 
     * @return void
     */
    public function verifConnexion()
    {
        // Récupération des données du formulaire
        $donneesFormulaire = [
            'mail' => htmlspecialchars($_POST['mail'], ENT_QUOTES,) ?? null,
            'mdp' => htmlspecialchars($_POST['mdp'], ENT_QUOTES) ?? null,
        ];

        // Validation des données
        $erreurs = Validator::validerConnexion($donneesFormulaire);
        if ($erreurs) {
            $template = $this->getTwig()->load('connexion.html.twig');
            echo $template->render(['erreurs' => $erreurs]);
            return;
        }

        $mail = $donneesFormulaire['mail'];
        $mdp = $donneesFormulaire['mdp'];
        $managerUtilisateur = new UtilisateurDao($this->getPdo());

        if(!$managerUtilisateur->estValide($mail) && $managerUtilisateur->emailExiste($mail)){ 
            $erreurs[] = "Veuillez activer votre compte";
            $template = $this->getTwig()->load('connexion.html.twig');
            echo $template->render(['erreurs' => $erreurs]);
            return;
        }

        $utilisateur = $managerUtilisateur->findByMail($mail);
        if ($utilisateur && password_verify($mdp, $utilisateur->getMotDePasse())) {
            $managerUtilisateur->verifierDerniereSauvegarde();
            $_SESSION['utilisateur'] = serialize($utilisateur);
            $this->getTwig()->addGlobal('utilisateurConnecte', $utilisateur);
            header("Location: index.php");
        } else {
            $template = $this->getTwig()->load('connexion.html.twig');
            echo $template->render(['message' => "L'adresse mail ou le mot de passe est incorrect"]);
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

            'idUtilisateur' => $_POST['idUtilisateur'] ?? null,
            'pseudo' => htmlspecialchars($_POST['pseudo'] ?? null, ENT_QUOTES),
            'photoProfil' => htmlspecialchars($_POST['photoProfil'] ?? 'default.png', ENT_QUOTES), // Image par défaut
            'banniereProfil' => htmlspecialchars($_POST['banniereProfil'] ?? "default.png", ENT_QUOTES), // Image par défaut
            'dateNaiss' => htmlspecialchars($_POST['dateNaiss'] ?? null, ENT_QUOTES),
            'mail' => htmlspecialchars($_POST['mail'] ?? null, ENT_QUOTES),
            'mdp' => htmlspecialchars($_POST['mdp'] ?? null, ENT_QUOTES),
            'mdpVerif' => htmlspecialchars($_POST['mdpVerif'] ?? null, ENT_QUOTES),
            'role' => htmlspecialchars($_POST['role'] ?? 'utilisateur', ENT_QUOTES), // Role par défaut
            'bio' => htmlspecialchars($_POST['bio'] ?? ' ', ENT_QUOTES), // Bio par défaut
            'valide' => $_POST['valide'] ?? 0
        ];
        $url = $_POST['currentUrl'];
        
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
            'bio' => [
                'obligatoire' => false,
                'type' => 'string',
                'longueur_max' => 255,
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
        //Verif en plus (pseudo)

        // Hachage du mot de passe
        $donneesFormulaire['mdp'] = password_hash($donneesFormulaire['mdp'], PASSWORD_BCRYPT);

        // Création de l'utilisateur
        $utilisateur = new Utilisateur(

            $donneesFormulaire['idUtilisateur'],
            htmlspecialchars_decode($donneesFormulaire['pseudo'], ENT_QUOTES),
            htmlspecialchars_decode($donneesFormulaire['photoProfil'], ENT_QUOTES),
            htmlspecialchars_decode($donneesFormulaire['banniereProfil'], ENT_QUOTES),
            htmlspecialchars_decode($donneesFormulaire['mail'], ENT_QUOTES),
            htmlspecialchars_decode($donneesFormulaire['mdp'], ENT_QUOTES),
            htmlspecialchars_decode($donneesFormulaire['role'], ENT_QUOTES),
            htmlspecialchars_decode($donneesFormulaire['bio'], ENT_QUOTES),
            $donneesFormulaire['valide']
        );
        
        // Sauvegarde dans la base de données
        $managerUtilisateur->creerUtilisateur($utilisateur);

        $email = htmlspecialchars_decode($donneesFormulaire['mail'], ENT_QUOTES);
        $utilisateur = $managerUtilisateur->findByMail($email);

        // Génère un token unique pour la réinitialisation
        $token = bin2hex(random_bytes(32));
        $tokenCrypt = password_hash($token, PASSWORD_BCRYPT);
        $idUtilisateur = $utilisateur->getIdUtilisateur();

        $expiresAt = date('Y-m-d H:i:s', time() + 3600); // Token valide pour 1 heure

        // Enregistre le token dans la base de données
        $managerUtilisateur->enregistrerToken($idUtilisateur, $tokenCrypt, $expiresAt);

        // Encode l'ID et le token pour les passer de manière sécurisée dans l'URL
        $idEncoded = urlencode(base64_encode($idUtilisateur));
        $tokenEncoded = urlencode(base64_encode($token));

        // Crée le lien de réinitialisation
        $parts = explode('~', $url);
        $util = explode('/', $parts[1])[0];
        
        $lienReset = "http://lakartxela.iutbayonne.univ-pau.fr/~" . $util . "/SAE3.01/Temporairement_VHS/Video-Home-Share/index.php?controleur=utilisateur&methode=verifMail&id=$idEncoded&token=$tokenEncoded";

        // Envoie un email avec le lien de réinitialisation
        $sujet = "Activez votre compte !";
        $message = "Bonjour,\n\nCliquez sur le lien ci-dessous pour active votre compte :\n$lienReset\n\nSi vous n'avez pas creer de compte, ignorez cet email.";
        mail($email, $sujet, $message);

        // Redirection vers la page de connexion
        $template = $this->getTwig()->load('connexion.html.twig');
        echo $template->render(['message' => "Veuillez regarder votre boite mail afin d'activer votre compte"]);
        exit;
    }

    public function verifMail()
    {
        // Récupérer le token depuis l'URL
        $id = base64_decode(urldecode($_GET['id']));
        $token = base64_decode(urldecode($_GET['token']));

        $managerUtilisateur = new UtilisateurDao($this->getPDO());
        $tokenCrypt = $managerUtilisateur->getTokenById($id);

        if (password_verify($token, $tokenCrypt))
        { 
            $managerUtilisateur->activerCompte($id);
            $managerUtilisateur->supprimerToken($tokenCrypt);
        }
        header('Location: index.php?controleur=utilisateur&methode=connexion');
        exit();
    }

    /**
     * @brief Déconnecte un utilisateur et le redirige vers la page d'accueil
     * @details Détruit la session de l'utilisateur et le redirige vers la page d'accueil
     * @author Thibault CHIPY
     * 
     * @return void
     */
    public function deconnexion()
    {
        session_destroy();
        header('Location: index.php');
    }


    /**
     * @brief Affiche la page de paramètres de l'utilisateur connecté
     * @details Affiche la page de paramètres de l'utilisateur connecté
     * 
     * @return void
     */
    public function traiterContact()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = htmlspecialchars($_POST['name']);
            $email = htmlspecialchars($_POST['mail']);
            $message = htmlspecialchars($_POST['message']);
            // Valide que l'email a été soumis et qu'il est correct
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['message'] = "Veuillez entrer une adresse email valide.";
                header('Location: index.php?controleur=utilisateur&methode=traiterContact');
                exit();
            }
            if (DB_HOST === 'localhost') {
                try {
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = EMAIL_ADDRESS;
                    $mail->Password = EMAIL_PASSWORD;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    

                    $mail->setFrom($email, $name);
                    $mail->addAddress(EMAIL_ADDRESS, 'VHS Contact');
                    $mail->addReplyTo($email, $name);

                    $mail->Subject = "VHS : Nouveau message de $name";
                    $mail->Body = "Nom : $name\nEmail : $email\n\nMessage :\n$message";

                    $mail->send();
                    $feedback = 'Votre message a bien été envoyé.';
                } catch (Exception $e) {
                    $feedback = 'Erreur lors de l\'envoi du mail : ' . $e->getMessage();
                }
            } 
            elseif (DB_HOST === 'lakartxela.iutbayonne.univ-pau.fr') {
                $sujet = "VHS : Nouveau message de $name avec le mail $email";
                mail(EMAIL_ADDRESS, $sujet, $message);
                $feedback = 'Mail enové avec succés.';
            } else {
                $feedback = 'Hôte non pris en charge.';
            }

            // Charger la vue avec un message de feedback
            $template = $this->getTwig()->load('index.html.twig');
            echo $template->render(['message' => $feedback]);
        }
    }

    public function afficherCommentairesUtilisateur()
    {
        if (isset($_GET['idUtilisateur'])) {
            $idUtilisateur = (int) $_GET['idUtilisateur'];

            // Récupération des commentaires de l'utilisateur
            $managerCommentaire = new CommentaireDAO($this->getPdo());
            $commentaires = $managerCommentaire->findCommentairesByIdUtilisateur($idUtilisateur);

            // Récupération des infos utilisateur
            $managerUtilisateur = new UtilisateurDao($this->getPdo());
            $utilisateur = $managerUtilisateur->find($idUtilisateur);

            // Passer les données au template
            $template = $this->getTwig()->load('commentairesUtilisateur.html.twig');
            echo $template->render([
                'utilisateur' => $utilisateur,
                'commentaires' => $commentaires,
            ]);
        } else {
            echo "Identifiant utilisateur manquant !";
        }
    }
}

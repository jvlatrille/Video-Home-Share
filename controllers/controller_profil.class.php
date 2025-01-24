<?php

/**
 * @file controller_profil.class.php
 * @author Despré-Hildevert Léa, Léval Noah
 * @brief Controleur des 3 pages qui composent la page de profil
 * @remark Pages Notification et APropos faites par Léa et page Paramètres faite par Noah
 * @version 2.0
 * @date 14/11/2024
 */

class ControllerProfil extends Controller
{
    /**
     * @brief Constructeur du controler de profil
     * @param \Twig\Environment $twig Environnement Twig
     * @param \Twig\Loader\FilesystemLoader $loader Loader Twig
     */
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    /**
     * @brief Affiche la page de paramétre du profil
     *
     * @return void
     */
    public function afficherFormulaire()
    {
        // Vérifie si un utilisateur est connecté
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);

            $template = $this->getTwig()->load('profilParametres.html.twig');
            echo $template->render(['utilisateur' => $utilisateurConnecte]);
            return; // Arrête l'exécution de la méthode sinon on a un double affichage
        }

        // Sinon, affiche la page de connexion
        $template = $this->getTwig()->load('connexion.html.twig');
        echo $template->render();
    }

    /**
     * @brief Change le pseudonyme de l'utilisateur
     *
     * @return void
     */
    public function changerPseudo() {
        if (isset($_SESSION['utilisateur'])) {
            
            $regles = [
                'pseudo' => [
                'obligatoire' => false,
                'type' => 'string',
                'longueur_min' => 5,
                'longueur_max' => 40,
                'format' => '/^[a-zA-ZÀ-ÿ0-9\'-]+$/'
                ]
            ];

            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            $this->getTwig()->addGlobal('utilisateurConnecte', $utilisateurConnecte);

            $id = $utilisateurConnecte->getIdUtilisateur();
            $newPseudo = isset($_POST['pseudo']) ? (string) $_POST['pseudo'] : null;
            $donnees = ["pseudo" => $newPseudo];

            // Vérification des données reçues
            $validator = new Validator($regles);
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

            // Mise à jour de la session avec les nouvelles données
            $_SESSION['utilisateur'] = serialize($utilisateur);

            header('Location: index.php?controleur=profil&methode=afficherFormulaire');
            exit();
        
        }  
    }  
     
    /**
     * @brief Change le bio de l'utilisateur
     *
     * @return void
     */
    public function changerBio() { 
        if (isset($_SESSION['utilisateur'])) {
            $regles = [
                'bio' => [
                    'obligatoire' => false,
                    'type' => 'string',
                    'longueur_min' => 0,
                    'longueur_max' => 255,
                    'format' => '/^[^\u2028\u2029\u00A0]+$/'
                ],
            ];
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            $this->getTwig()->addGlobal('utilisateurConnecte', $utilisateurConnecte);

            $id = $utilisateurConnecte->getIdUtilisateur();
            $bio = isset($_POST['bio']) ? trim($_POST['bio']) : null;
            $donnees = ["bio" => $bio];

            // Vérification des données reçues
            $validator = new Validator($regles);
            $valides = $validator->valider($donnees);
        
            // Interaction avec le DAO pour mettre à jour le pseudo
            $managerUtilisateur = new UtilisateurDao($this->getPdo());
            if ($valides)
            {
                $reussite = $managerUtilisateur->changerBio($id, $bio);
            }
        
            // Génération d'un message en fonction du succès ou de l'échec
            $messages = $validator->getMessagesErreurs();
            $utilisateur = $managerUtilisateur->find($id);
        
            // Mise à jour de la session avec les nouvelles données
            $_SESSION['utilisateur'] = serialize($utilisateur);
            header('Location: index.php?controleur=profil&methode=afficherFormulaire');
            exit();

        }
    }  
     
    /**
     * @brief Change le mail de l'utilisateur
     *
     * @return void
     */
    public function changerMail() { 
        if (isset($_SESSION['utilisateur'])) {
            $regles = [
                'mail' => [
                    'obligatoire' => false,
                    'type' => 'string',
                    'longueur_min' => 5,
                    'longueur_max' => 255,
                    'format' => FILTER_VALIDATE_EMAIL
                ],
            ];
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            $this->getTwig()->addGlobal('utilisateurConnecte', $utilisateurConnecte);

            $id = $utilisateurConnecte->getIdUtilisateur();
            $newMail = isset($_POST['mail']) ? trim($_POST['mail']) : null;
            $donnees = ["mail" => $newMail];

            // Vérification des données reçues
            $validator = new Validator($regles);
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
        
            // Mise à jour de la session avec les nouvelles données
            $_SESSION['utilisateur'] = serialize($utilisateur);
            header('Location: index.php?controleur=profil&methode=afficherFormulaire');
            exit();

        }
    }

    /**
     * @brief Change la photo de profil de l'utilisateur
     *
     * @return void
     */
    public function changerPhotoProfil()
    {
        if (isset($_SESSION['utilisateur'])) {
            $regles = [];

            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            $this->getTwig()->addGlobal('utilisateurConnecte', $utilisateurConnecte);

            $userId = $utilisateurConnecte->getIdUtilisateur();
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

            $utilisateur = $managerUtilisateur->find($userId);
        
            // Mise à jour de la session avec les nouvelles données
            $_SESSION['utilisateur'] = serialize($utilisateur);
        
            header('Location: index.php?controleur=profil&methode=afficherFormulaire');
        }
    }
    
    /**
     * @brief Change la banniere de l'utilisateur
     *
     * @return void
     */
    public function changerBanniere()
    {
        if (isset($_SESSION['utilisateur'])) {
            $regles = [];

            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            $this->getTwig()->addGlobal('utilisateurConnecte', $utilisateurConnecte);

            $userId = $utilisateurConnecte->getIdUtilisateur();
            $messages = [];
            $managerUtilisateur = new UtilisateurDao($this->getPdo());
            
            // Vérifier si un fichier a été envoyé
            if (isset($_FILES['banniere']) && $_FILES['banniere']['error'] == 0) {
                // Valider le fichier photo
                $validator = new Validator($regles);
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

            $utilisateur = $managerUtilisateur->find($userId);
        
            // Mise à jour de la session avec les nouvelles données
            $_SESSION['utilisateur'] = serialize($utilisateur);

            header('Location: index.php?controleur=profil&methode=afficherFormulaire');
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
        $token = isset($_GET['token']) ? $_GET['token'] : null;
        $tokenCrypt = password_hash($token, PASSWORD_BCRYPT);
        $managerUtilisateur = new UtilisateurDao();
        password_verify($token, $managerUtilisateur->getTokenInfo());

        var_dump($token, $tokenCrypt);
    
        if ($token === null) {
            // Gérer le cas où le token est absent
            echo "Lien invalide ou token manquant.";
            return;
        }
    
        // Transmettre le token au template Twig
        $template = $this->getTwig()->load('profilParametresMDP.html.twig');
        echo $template->render([
            'token' => $tokenCrypt // Transmettre le token au formulaire
        ]);
    }

    /**
     * @brief Vérifie que l'utilisateur à bien rentrer son mot de passe avant de pouvoir le changer
     *
     * @return void
     */
    public function verifierMDP()
    {
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            $this->getTwig()->addGlobal('utilisateurConnecte', $utilisateurConnecte);

            $message = "";

            $mdp = isset($_POST['MDP']) ? trim($_POST['MDP']) : null;
            if(password_verify($mdp, $utilisateurConnecte->getMotDePasse()))
            {
                $mdpValide = True;
            }
            else
            {
                $mdpValide = False;
                $message = "mot de passe incorrect";
            }

            $template = $this->getTwig()->load('profilParametresMdP.html.twig');
            echo $template->render([
                'utilisateur' => $utilisateurConnecte,
                'mdpValide' => $mdpValide,
                'message' => $message
            ]);
        }
    }

    /**
     * @brief Change le mot de passe de l'utilisateur aprés modification
     *
     * @return void
     */
    public function changerMdp()
    {
            $mdp1=isset($_POST['MDP1'])?$_POST['MDP1']:null;
            $mdp2=isset($_POST['MDP2'])?$_POST['MDP2']:null;
            $token = isset($_POST['token']) ? $_POST['token'] : null;

            $managerUtilisateur = new UtilisateurDao($this->getPdo());
            $id = $managerUtilisateur->getIdByToken($token);

            if (!$managerUtilisateur->estRobuste($mdp1))
            {
                $template = $this->getTwig()->load('profilParametresMdP.html.twig');
                echo $template->render([
                    'mdpValide' => True,
                    'message' => "Le mot de passe n'est pas assez robuste"
                ]);
                return;
            }

            if($mdp1 != $mdp2){
                $template = $this->getTwig()->load('profilParametresMdP.html.twig');
                echo $template->render([
                    'mdpValide' => True,
                    'message' => 'Les mots de passe ne correspondent pas'
                ]);
                return;
            }
            
            $mdpHash = password_hash($mdp1, PASSWORD_BCRYPT);
            $managerUtilisateur = new UtilisateurDao($this->getPdo());
            $managerUtilisateur->changerMdp($id, $mdpHash);

            $managerUtilisateur->supprimerToken($token);

            // Mise à jour de la session avec les nouvelles données
            header('Location: index.php?controleur=utilisateur&methode=connexion');

    }
    




    
    /**
     * @brief Affiche toutes les notifications de l'utilisateur connecté sur la page notification
     *
     * @return void
     */
    //Fonction pour afficher toutes les notif d'une personne 
    public function listerNotif()
    {
        // Vérifie si un utilisateur est connecté
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);


            //Recupere les notifications
            $managerNotif=New NotificationDao($this->getPdo());
            $notifListe=$managerNotif->findAll($utilisateurConnecte->getIdUtilisateur());
            //Generer la vue avec les notifications de l'utilisateur
            $template = $this->getTwig()->load('profilNotifications.html.twig');
            echo $template->render(['notifListe' => $notifListe]);
            
        }
        else {
            // Redirige vers la page de connexion
            header('Location: index.php?controleur=utilisateur&methode=connexion');
        }
    
    }

    
    /**
     * @brief Affiche une notification 
     *
     * @return void
     */
    //Fonction pour afficher une notification
    public function afficherNotif()
    {
        $id = isset($_GET['idNotif']) ? $_GET['idNotif'] : null;

        if ($id === null) {
            $template = $this->getTwig()->load('profilNotifications.html.twig');
            echo $template->render();

        }
        
        //Recupere la notification
        $managerNotif=New NotificationDao($this->getPdo());
        $contenuNotif=$managerNotif->findNotif($id);
    
        //Generer la vue
        $template = $this->getTwig()->load('uneNotification.html.twig');
        
        echo $template->render(['contenuNotif'=>$contenuNotif]);

    }

    
    /**
     * @brief Supprime une notification de l'utilisateur connecté
     *
     * @return void
     */
    //Fonction pour supprimer une notification
    public function supprimerUneNotif()
    {
        // Vérifie si un utilisateur est connecté
    if (isset($_SESSION['utilisateur'])) {
        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);

        $idUtilisateur = $utilisateurConnecte->getIdUtilisateur();
        //Recupere l'id de la notification
        $idNotif = isset($_GET['idNotif']) ? $_GET['idNotif'] : null;
        
        //Supprime la notification
        $managerNotif = new NotificationDao($this->getPdo());
        $managerNotif->supprimerUneNotification($idNotif, $idUtilisateur);
        
        //Generer la vue
        $template = $this->getTwig()->load('profilNotifications.html.twig');

        //Redirige vers la liste des notifications
        header('Location: index.php?controleur=profil&methode=listerNotif&id='.$idUtilisateur.'');
    }
}

    /**
     * @brief Supprime toutes les notifications de l'utilisateur connecté
     *
     * @return void
     */
    //Fonction pour supprimer toutes les notifications d'une personne
    public function supprimerToutesLesNotifs()
    {
        // Vérifie si un utilisateur est connecté
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            //Recupere l'id de l'utilisateur'
            $idUtilisateur = $utilisateurConnecte->getIdUtilisateur();
            
            //Supprime la notification
            $managerNotif = new NotificationDao($this->getPdo());
            $managerNotif->supprimerToutesLesNotifs($idUtilisateur);
            
            //Generer la vue
            $template = $this->getTwig()->load('profilNotifications.html.twig');

            //Redirige vers la liste des notifications
            header('Location: index.php?controleur=profil&methode=listerNotif&id='.$idUtilisateur.'');
        }
    }


    /**
     * @brief Affiche les messages postés par l'utilisateur connecté sur la page APropos
     *
     * @return void
     */

    //Fonction pour afficher les informations de A Propos
    public function afficherAPropos()
    {        
        // Vérifie si un utilisateur est connecté
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            //Recupere l'id de l'utilisateur'
            $idUtilisateur = $utilisateurConnecte->getIdUtilisateur();


            // Récupère les messages postés par l'utilisateur
            $managerMessage = new MessageDao($this->getPdo());
            $messageListe = $managerMessage->chargerAPropos($idUtilisateur);

            // Génère la vue 
            $template = $this->getTwig()->load('profilAPropos.html.twig');
            echo $template->render(['messageListe' => $messageListe,'utilisateur' => $utilisateurConnecte]);

        }
        else {
            // Sinon, affiche la page de connexion
            $template = $this->getTwig()->load('connexion.html.twig');
            echo $template->render();
        }
    }
    
}
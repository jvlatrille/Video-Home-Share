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

            $breadcrumb = [
                ['title' => 'Accueil', 'url' => 'index.php'],
                ['title' => 'Mon profil', 'url' => 'index.php?controleur=profil&methode=afficherApropos'],
                ['title' => 'Paramètres', 'url' => 'index.php?controleur=profil&methode=afficherFormulaire']
            ];
            $template = $this->getTwig()->load('profilParametres.html.twig');
            echo $template->render(['utilisateur' => $utilisateurConnecte, 'breadcrumb' => $breadcrumb]);
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
                    'validation_personnalisee' => function($valeur) {
                        return preg_match('/^[^\x{2028}\x{2029}\x{00A0}]+$/u', $valeur) ? true : "Le texte ne doit pas contenir de caractères spéciaux.";
                    },
                ],
            ];
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);

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
     * @brief Envoie un email pour changer le mail de l'utilisateur
     *
     * @return void
     */
    public function boutonChangerMail() { 
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
            $ancienMail = $utilisateurConnecte->getAdressMail();
            $newMail = isset($_POST['mail']) ? trim($_POST['mail']) : null;
            $donnees = ["mail" => $newMail];

            // Vérification des données reçues
            $validator = new Validator($regles);
            $valides = $validator->valider($donnees);

            if (!$valides)
            {
                $erreurs[] = "Veuillez entrez une adresse mail valide";
                $template = $this->getTwig()->load('profilParametres.html.twig');
                echo $template->render(['erreurs' => $erreurs]);
                return;
            }

            // Verification si le mail est different
            if($ancienMail === $newMail || empty($newMail))
            {
                $erreurs[] = "Veuillez entrez une adresse mail différente";
                $template = $this->getTwig()->load('profilParametres.html.twig');
                echo $template->render(['erreurs' => $erreurs]);
                return;
            }

            // Interaction avec le DAO pour evoyer le lien pour mettre à jour le mail
            $managerUtilisateur = new UtilisateurDao($this->getPdo());
            
            $token = bin2hex(random_bytes(32));
            $tokenCrypt = password_hash($token, PASSWORD_BCRYPT);
    
            $expiresAt = date('Y-m-d H:i:s', time() + 3600); // Token valide pour 1 heure
    
            // Enregistre le token dans la base de données
            $managerUtilisateur->enregistrerToken($id, $tokenCrypt, $expiresAt);
    
            // Encode l'ID et le token pour les passer de manière sécurisée dans l'URL
            $idEncoded = urlencode(base64_encode($id));
            $tokenEncoded = urlencode(base64_encode($token));
    
            // Crée le lien de réinitialisation
            $lienReset = "http://lakartxela.iutbayonne.univ-pau.fr/~nleval/SAE3.01/Temporairement_VHS/Video-Home-Share/index.php?controleur=profil&methode=pageChangerMail&id=$idEncoded&token=$tokenEncoded";
    
            // Envoie un email avec le lien de réinitialisation
            $sujet = "Changer votre adresse mail";
            $message = "Bonjour,\n\nCliquez sur le lien ci-dessous pour changer votre adresse mail :\n$lienReset\n\nSi vous n'avez pas demandé de changement de mail, ignorez cet email.";
            mail($newMail, $sujet, $message);

            $managerUtilisateur->desactiverCompte($id);
            $managerUtilisateur->changerMail($id, $newMail);

            // Génération d'un message en fonction du succès ou de l'échec
            $messages = $validator->getMessagesErreurs();
            
            // Deconnexion
            header('Location: index.php?controleur=utilisateur&methode=deconnexion');
            exit();

        }
    }

    /**
     * @brief Affiche la page pour confirmer le changement du mail
     * @author Noah LÉVAL 
     *
     * @return void
     */
    public function pageChangerMail(){
        // Récupérer le token depuis l'URL
        $id = base64_decode(urldecode($_GET['id']));
        $token = base64_decode(urldecode($_GET['token']));

        $managerUtilisateur = new UtilisateurDao($this->getPDO());
        $tokenCrypt = $managerUtilisateur->getTokenById($id);

        if (empty($token) || empty($tokenCrypt) || !password_verify($token, $tokenCrypt))
        { 
            $erreurs[] = "Erreur de token";
            $template = $this->getTwig()->load('connexion.html.twig');
            echo $template->render(['erreurs' => $erreurs]);
            return;
        }

        // Transmettre le token au template Twig
        $template = $this->getTwig()->load('changerMail.html.twig');
        echo $template->render([
            'token' => $tokenCrypt // Transmettre le token au formulaire
        ]);
        return Null;
    }

    /**
     * @brief Vérifie la saisie du mot de passe et change le mail
     * @author Noah LÉVAL 
     *
     * @return void
     */
    public function changerMail()
    {
        // Récupérer le token et l'id depuis l'URL et le mdp depuis le formulaire
        $mdp = isset($_POST['MDP'])?$_POST['MDP']:null;
        $token = isset($_POST['token']) ? $_POST['token'] : null;

        $managerUtilisateur = new UtilisateurDao($this->getPdo());
        $id = $managerUtilisateur->getIdUserByToken($token);
        $mdpCrypt = $managerUtilisateur->getMdpById($id);

        if(empty($mdp) || !password_verify($mdp, $mdpCrypt))
        {
            $erreurs[] = "Mot de passe incorrect";
            $template = $this->getTwig()->load('changerMail.html.twig');
            echo $template->render([
                'erreurs' => $erreurs,
                'token' => $token
            ]);
            return;
        }

        $managerUtilisateur->activerCompte($id); 
        $managerUtilisateur->supprimerToken($token);
        header('Location: index.php?controleur=utilisateur&methode=connexion');
        exit();
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
            $userPseudo = $utilisateurConnecte->getPseudo();
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
                    $fileExtension = strtolower(pathinfo($_FILES['banniere']['name'], PATHINFO_EXTENSION));
                    $uploadDir = 'img/banniere/';
                    $fileName = "$userId" . "_" . "$userPseudo" . ".$fileExtension";
                    $filePath = $uploadDir . $fileName;
                    
                    // Supprimer l'ancienne banniere si elle existe
                    $anciennePhoto = glob($uploadDir . "$userId" . "_*.{jpg,jpeg,png,gif}", GLOB_BRACE);
                    foreach ($anciennePhoto as $fichier) {
                        if (is_file($fichier)) {
                            unlink($fichier);
                        }
                    }
                    
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
            $idMessage = isset($_GET['idMessage']) ? $_GET['idMessage'] : null;


            //Recupere les notifications
            $managerNotif=New NotificationDao($this->getPdo());
            $notifListe=$managerNotif->findAll($utilisateurConnecte->getIdUtilisateur());
            $nomForum=$managerNotif->recupNomForum($idMessage);

            $breadcrumb = [
                ['title' => 'Accueil', 'url' => 'index.php'],
                ['title' => 'Mon profil', 'url' => 'index.php?controleur=profil&methode=afficherApropos'],
                ['title' => 'Notifications', 'url' => 'index.php?controleur=profil&methode=listerNotif']
            ];

            //Generer la vue avec les notifications de l'utilisateur
            $template = $this->getTwig()->load('profilNotifications.html.twig');
            echo $template->render(['notifListe' => $notifListe, 'nomForum'=>$nomForum,'breadcrumb'=>$breadcrumb]);//, 'nomForum'=>$nomForum
            
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
        
    $breadcrumb = [
        ['title' => 'Accueil', 'url' => 'index.php'],
        ['title' => 'Mon profil', 'url' => 'index.php?controleur=profil&methode=afficherApropos'],
        ['title' => 'Notifications', 'url' => 'index.php?controleur=profil&methode=listerNotif'],
        ['title' => 'Notification', 'url' => 'index.php?controleur=profil&methode=afficherNotif&idNotif='.$id.'']
    ];
        //Generer la vue
        $template = $this->getTwig()->load('uneNotification.html.twig');
        
        echo $template->render(['contenuNotif'=>$contenuNotif,'breadcrumb'=>$breadcrumb]);

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
            $this->getTwig()->addGlobal('utilisateurConnecte', $utilisateurConnecte);

            //Recupere l'id de l'utilisateur'
            $idUtilisateur = $utilisateurConnecte->getIdUtilisateur();
            
            $managerUtilisateur = new UtilisateurDao($this->getPdo());


            // Récupère les messages postés par l'utilisateur
            $managerMessage = new MessageDao($this->getPdo());
            $messageListe = $managerMessage->chargerAPropos($idUtilisateur);


            // Récupère les commenataires postés par l'utilisateur
            $managerComm = new CommentaireDao($this->getPdo());
            $commentaires = $managerComm->chargerComm($idUtilisateur);

            //Récupère le titre de l'OA
            // $managerOa = new OADao($this->getPdo());
            // $titreOA =$managerOa->find();


            $utilisateur = $managerUtilisateur->find($idUtilisateur);
            $_SESSION['utilisateur'] = serialize($utilisateur);

            $breadcrumb = [
                ['title' => 'Accueil', 'url' => 'index.php'],
                ['title' => 'Mon profil', 'url' => 'index.php?controleur=profil&methode=afficherFormulaire'],
            ];

            // Génère la vue 
            $template = $this->getTwig()->load('profilAPropos.html.twig');
            echo $template->render(['messageListe' => $messageListe, 'commentaires' => $commentaires, 'utilisateur' => $utilisateurConnecte,'breadcrumb' => $breadcrumb]); //'titreOA'=>$titreOA

            
        }
        else {
            // Sinon, affiche la page de connexion
            $template = $this->getTwig()->load('connexion.html.twig');
            echo $template->render();
        }
    }

    public function afficherNomForum()
    {
        $idMessage = isset($_GET['idMessage']) ? $_GET['idMessage'] : null;

        // if ($id === null) {
        //     $template = $this->getTwig()->load('profilNotifications.html.twig');
        //     echo $template->render();

        // }
        
        //Recupere la notification
        $managerNotif=New NotificationDao($this->getPdo());
        $nomForum=$managerNotif->recupNomForum($idMessage);
    
        //Generer la vue
        $template = $this->getTwig()->load('profilNotifications.html.twig');
        
        echo $template->render(['nomForum'=>$nomForum]);

    }


    
  
}
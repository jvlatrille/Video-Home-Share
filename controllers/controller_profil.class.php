<?php

class ControllerProfil extends Controller
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
                'format' => '/^[a-zA-ZÀ-ÿ0-9\'-]+$/'
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


    // Changer de pseudo
    public function changerPseudo() {
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            $this->getTwig()->addGlobal('utilisateurConnecte', $utilisateurConnecte);

            $id = $utilisateurConnecte->getIdUtilisateur();
            $newPseudo = isset($_POST['pseudo']) ? (string) $_POST['pseudo'] : null;
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

            $template = $this->getTwig()->load('profilParametres.html.twig');
            echo $template->render([
                'utilisateur' => $utilisateur,
                'message' => $messages
            ]);
        }  
    }  
     
    
    // Changer de Mail
    public function changerMail() { 
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            $this->getTwig()->addGlobal('utilisateurConnecte', $utilisateurConnecte);

            $id = $utilisateurConnecte->getIdUtilisateur();
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
    }

    public function changerPhotoProfil()
    {
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            $this->getTwig()->addGlobal('utilisateurConnecte', $utilisateurConnecte);

            $userId = $utilisateurConnecte->getIdUtilisateur();
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
    }
    

    public function changerBanniere()
    {
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            $this->getTwig()->addGlobal('utilisateurConnecte', $utilisateurConnecte);

            $userId = $utilisateurConnecte->getIdUtilisateur();
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
    }

    //Fonction pour afficher la page de changement de mot de passe
    public function pageChangerMDP()
    {
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            $this->getTwig()->addGlobal('utilisateurConnecte', $utilisateurConnecte);

            $template = $this->getTwig()->load('profilParametresMdP.html.twig');
            echo $template->render(['utilisateur' => $utilisateurConnecte]);
        }
    }

    //Fonction pour vérifier le mot de passe
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

    //Fonction pour changer de mot de passe
    public function changerMdp()
    {
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            $this->getTwig()->addGlobal('utilisateurConnecte', $utilisateurConnecte);

            $mdp1=isset($_POST['MDP1'])?$_POST['MDP1']:null;
            $mdp2=isset($_POST['MDP2'])?$_POST['MDP2']:null;

            $managerUtilisateur = new UtilisateurDao($this->getPdo());
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
            $managerUtilisateur->changerMdp($utilisateurConnecte->getIdUtilisateur(), $mdpHash);

            $template = $this->getTwig()->load('profilParametres.html.twig');
            echo $template->render([]);
        }
    }
    
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
    //Fonction pour supprimer toutes les notifications d'une personne
    public function supprimerToutesLesNotifs()
    {
        // Vérifie si un utilisateur est connecté
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            //Recupere l'id de la notification
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
}
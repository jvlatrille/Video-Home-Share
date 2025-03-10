<?php
class ControllerMessage extends Controller
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    // Afficher tous les messages
    public function listerMessage()
    {
        // Vérifie si le paramètre idForum est dans l'URL
        if (!isset($_GET['idForum']) || empty($_GET['idForum'])) {
            $this->afficherErreur("Le paramètre idForum est manquant.");
        }        

        // Récupère l'identifiant du forum
        $idForum = (int) $_GET['idForum'];
        $managerForum = new ForumDAO($this->getPdo());
        $forum = $managerForum->find($idForum);


        // Récupère tous les messages
        $managerMessage = new MessageDAO($this->getPdo());
        $messagesListe = $managerMessage->findAll($idForum);

        $breadcrumb = [
            ['title' => 'Accueil', 'url' => 'index.php'],
            ['title' => 'Liste des forums', 'url' => 'index.php?controleur=forum&methode=listerForum'],
            ['title' => $forum->getNom(), 'url' => 'index.php?controleur=message&methode=listerMessage&idForum=' . $idForum]
        ];

        // Génère la vue
        $template = $this->getTwig()->load('forum_detail.html.twig');
        echo $template->render([
            'messageListe' => $messagesListe,
            'forum' => $forum,
            'breadcrumb' => $breadcrumb
        ]);
    }

    public function ajouterMessage()
    {
    // Vérifie si l'utilisateur est connecté
    if (isset($_SESSION['utilisateur'])) {
        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);

        // Récupération des données
        $idForum = $_POST['idForum'] ?? $_GET['idForum'] ?? null;
        $contenu = $_POST['contenu'] ?? $_GET['contenu'] ?? null;
        $pseudo = $utilisateurConnecte->getPseudo();
        $photoProfil = $utilisateurConnecte->getPhotoProfil();
        $idUtilisateur = $utilisateurConnecte->getIdUtilisateur();
        
        // Création d'un nouveau message via le DAO
        $managerMessage = new MessageDao($this->getPdo());
        $message = new Message();
        $message->setContenu($contenu);
        $message->setNbLikes(0);
        $message->setNbDislikes(0);
        $message->setPseudo($pseudo);
        $message->setPhotoProfil($photoProfil);
        $message->setIdUtilisateur($idUtilisateur);
        $message->setIdForum($idForum);

            // Ajoute le message à la base de données
            $managerMessage->creerMessage($message);

        // Redirige vers la liste des messages du forum
        header("Location: index.php?controleur=message&methode=listerMessage&idForum=" . $idForum);
        exit;
    }
    }

    public function like()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idMessage'])) {
            $idMessage = (int)$_POST['idMessage'];


            if (isset($_SESSION['utilisateur'])) {
                $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
    
                //Recupere l'id de l'utilisateur'
                $idUtilisateur = $utilisateurConnecte->getIdUtilisateur();
            }

            // Récupère et incrémente le like
            $messageDAO = new MessageDAO($this->getPdo());
            $messageDAO->incrementLike($idMessage);
            
            $message = $messageDAO->creerNotif($idUtilisateur, $idMessage);


            // Redirige vers la liste des messages
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }



    public function dislike()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idMessage'])) {
            $idMessage = (int)$_POST['idMessage'];

            if (isset($_SESSION['utilisateur'])) {
                $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
    
                //Recupere l'id de l'utilisateur'
                $idUtilisateur = $utilisateurConnecte->getIdUtilisateur();
            }

            // Récupère et incrémente le dislike
            $messageDAO = new MessageDAO($this->getPdo());
            $messageDAO->incrementDislike($idMessage);

            $message = $messageDAO->creerNotif($idUtilisateur);

            // Redirige vers la liste des messages
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    /**
     * @brief Cette méthode affiche les messages les plus likés.
     * 
     * @author VINET LATRILLE Jules
     * @return void
     */
    public function afficherTopMessages()
    {
        $managerForum = new messageDAO($this->getPdo());
        $topMessages = $managerForum->getTopLikedMessages();
        $template = $this->getTwig()->load('index.html.twig');
        echo $template->render(['topMessages' => $topMessages]);
    }

    public function afficherMessagesUtilisateur()
    {
        if (isset($_GET['idUtilisateur'])) {
            $idUtilisateur = (int) $_GET['idUtilisateur'];

            // Récupération des messages de l'utilisateur
            $managerMessage = new MessageDAO($this->getPdo());
            $messages = $managerMessage->getMessagesByUser($idUtilisateur);

            // Récupération des infos utilisateur (optionnel)
            $managerUtilisateur = new UtilisateurDao($this->getPdo());
            $utilisateur = $managerUtilisateur->find($idUtilisateur);

            // Génération de la vue
            $template = $this->getTwig()->load('messagesUtilisateur.html.twig');
            echo $template->render([
                'utilisateur' => $utilisateur,
                'messages' => $messages
            ]);
        } else {
            $this->afficherErreur("Vous devez être connecté pour ajouter un message.");
        }
    }

    // public function insertMessageDansNotif(){
    //     // Vérifie si un utilisateur est connecté
    //     if (isset($_SESSION['utilisateur'])) {
    //         $utilisateurConnecte = unserialize($_SESSION['utilisateur']);

    //         //Recupere l'id de l'utilisateur'
    //         $idUtilisateur = $utilisateurConnecte->getIdUtilisateur();
            
    //         $managerMessage = new MessageDao($this->getPdo());
    //         $message = $managerMessage->creerNotif($idUtilisateur);

    //     }
    // }




    // public function afficherNotifMessage()
    // {        
    //     // Vérifie si un utilisateur est connecté
    //     if (isset($_SESSION['utilisateur'])) {
    //         $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            
    //         // Récupération des infos utilisateur (optionnel)
    //         $managerUtilisateur = new UtilisateurDao($this->getPdo());
    //         $utilisateur = $managerUtilisateur->find($idUtilisateur);
    //     }
            
    //     //     // $managerUtilisateur = new UtilisateurDao($this->getPdo());

    //     //     // Récupère les messages postés par l'utilisateur
    //     //     $managerMessage = new MessageDao($this->getPdo());
    //     //     $messageNotifListe = $managerMessage->creerNotif($idUtilisateur);


    //     //     $utilisateur = $managerUtilisateur->find($idUtilisateur);
    //     //     $_SESSION['utilisateur'] = serialize($utilisateur);

    //     //     // Génère la vue 
    //     //     $template = $this->getTwig()->load('profilAPropos.html.twig');
    //     //     echo $template->render(['messageNotifListe' => $messageNotifListe, 'utilisateur' => $utilisateurConnecte]); 
    //     // }
    //     else {
    //         // Sinon, affiche la page de connexion
    //         $template = $this->getTwig()->load('connexion.html.twig');
    //         echo $template->render();
    //     }


    //     //
    //     // Traitement des œuvres (idTMDB et type)
    //     //
    //     // $listeOeuvres = $donnees['OAs'] ?? [];
        
        

    //     // foreach ($listeOeuvres as $oeuvre) {
    //     //     if (is_string($oeuvre)) {
    //     //         $idTMDB=explode(':', $oeuvre);
    //     //         $oeuvresFormatees[] = [
    //     //             'idTMDB' => $idTMDB[0],
    //     //             'type' => $idTMDB[1],
    //     //         ];
    //     //     }
    //     // }
    //     $this->idNotif=$idNotif;
    //     $this->dateNotif = $dateNotif;
    //     $this->destinataire = $destinataire;
    //     $this->contenu = $contenu;
    //     $this->vu = $vu ??false; //permet que vu ne soit jamais null
    //     $this->idUtilisateur=$idUtilisateur;


    //     $messageNotif = [];

    //     // Création de la notification
    //     $managerNotification = new NotificationDao($this->getPdo());
    //     $notif = new Notification();
    //     $notif->setIdNotif($idNotif);
    //     $notif->setDateNotif($dateNotif);
    //     $notif->setDestinataire($destinataire);
    //     $notif->setContenu($contenu);
    //     $notif->setVu($vu);
    //     $notif->setIdUtilisateur($idUtilisateur);
    //     $managerNotification->creerNotif($notification);

    //     $idNouvelleNotif = $notif->getIdNotif();
        
    //     // Association des œuvres à la watchlist
    //     foreach ($messageNotif as $message) {
    //         $managerNotification->addMessageToNotification($idNouvelleNotif, $message['idNotif']);
    //     }
    //     // Redirection vers la liste des watchlists
    //     // header('Location: index.php?controleur=watchlist&methode=listerWatchList&id=' . $idUtilisateur);
    //     // exit();
    // }

    /**
     * @author VINET LATRILLE Jules
     * @brief Affiche une page d'erreur
     * @param string $message Message d'erreur à afficher
     */
    private function afficherErreur(string $message): void
    {
        $erreurController = new ErreurController($this->getTwig(), $this->getLoader());
        $erreurController->renderErreur($message);
        exit();
    }

}

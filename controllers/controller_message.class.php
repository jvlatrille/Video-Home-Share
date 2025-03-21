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

    public function modifierMessage()
    {
        // Vérifie si l'utilisateur est connecté
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            $idUtilisateur = $utilisateurConnecte->getIdUtilisateur();

            // Récupération des données
            $idMessage = $_POST['idMessage'] ?? $_GET['idMessage'] ?? null;
            $contenu = $_POST['contenu'] ?? $_GET['contenu'] ?? null;
            
            // Récupère le message via le DAO
            $managerMessage = new MessageDAO($this->getPdo());
            $message = $managerMessage->find($idMessage);

            // Modifie le contenu du message
            $message->setContenu($contenu);
            $managerMessage->modifierMessageDAO($message);

            // Redirige vers la liste des messages du forum
            header("Location: index.php?controleur=message&methode=listerMessage&idForum=" . $message->getIdForum());
            exit;
            
            
        } else {
            echo "Vous devez être connecté pour modifier un message.";
        }
    }

    public function supprimerMessage()
    {
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            $idUtilisateur = $utilisateurConnecte->getIdUtilisateur();

            // Récupération de l'identifiant du message
            $idMessage = $_GET['idMessage'] ?? null;

            // Récupère le message via le DAO
            $managerMessage = new MessageDAO($this->getPdo());
            $message = $managerMessage->find($idMessage);
            $managerMessage->supprimerMessageDAO($message);

            // Redirige vers la liste des messages du forum
            header("Location: index.php?controleur=message&methode=listerMessage&idForum=" . $message->getIdForum());
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
            
            $message = $messageDAO->creerNotif($idMessage, "Message like");


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

            $message = $messageDAO->creerNotif( $idMessage, "Message dislike");

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

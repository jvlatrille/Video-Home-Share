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
            die("Paramètre idForum manquant !");
        }

        // Récupère l'identifiant du forum
        $idForum = (int) $_GET['idForum'];
        $managerForum = new ForumDAO($this->getPdo());
        $forum = $managerForum->find($idForum);


        // Récupère tous les messages
        $managerMessage = new MessageDAO($this->getPdo());
        $messagesListe = $managerMessage->findAll($idForum);

        // Génère la vue
        $template = $this->getTwig()->load('forum_detail.html.twig');
        echo $template->render([
            'messageListe' => $messagesListe,
            'forum' => $forum
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

            // Récupère et incrémente le like
            $messageDAO = new MessageDAO($this->getPdo());
            $messageDAO->incrementLike($idMessage);

            // Redirige vers la liste des messages
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    public function dislike()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idMessage'])) {
            $idMessage = (int)$_POST['idMessage'];

            // Récupère et incrémente le dislike
            $messageDAO = new MessageDAO($this->getPdo());
            $messageDAO->incrementDislike($idMessage);

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
            echo "Identifiant utilisateur manquant !";
        }
    }
}

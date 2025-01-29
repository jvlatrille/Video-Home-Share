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
            'forum' => $forum]);
    }

    public function ajouterMessage()
{
    // Vérifie si l'utilisateur est connecté
    if (isset($_SESSION['utilisateur'])) {
        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);

        // Récupération des données
        $idForum = $_POST['idForum'] ?? null;
        $contenu = $_POST['contenu'] ?? null;
        $nbLikes = $_POST['nbLikes'] ?? 0;
        $nbDislikes = $_POST['nbDislikes'] ?? 0;
        $idUtilisateur = $utilisateurConnecte->getIdUtilisateur();

        // Création d'un nouveau message via le DAO
        $managerMessage = new MessageDao($this->getPdo());
        $message = new Message();
        $message->setIdForum($idForum);
        $message->setContenu($contenu);
        $message->setNbLikes($nbLikes);
        $message->setNbDislikes($nbDislikes);
        $message->setIdUtilisateur($idUtilisateur);

        // Ajoute le message à la base de données
        $managerMessage->creerMessage($message);

        // Redirige vers la liste des messages du forum
        header('Location: index.php?controleur=message&methode=listerMessage');
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

}
?>
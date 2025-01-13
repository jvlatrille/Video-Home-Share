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
        //Recuperer l'id forum
        $idForum = $_GET['idForum'];
        //Recupere les données du formulaire
        $contenu = isset($_POST['contenu']) ? $_POST['contenu'] : (isset($_GET['contenu']) ? $_GET['contenu'] : null);
        $managerMessage = new MessageDao($this->getPdo());
        $message = new Message();
        $message->setContenu($contenu);
        $message->setIdForum($idForum);
        $message->setNbLikes(0);
        $message->setNbDislikes(0);
        $message->setIdUtilisateur(1);
        $managerMessage->creerMessage($message);
        var_dump($message);
        
        //Redirige vers la liste des forums
        header('Location: index.php?controleur=message&methode=listerMessage&idForum=' .$idForum. '');
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
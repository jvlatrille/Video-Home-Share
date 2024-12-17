<?php
class ControllerMessage extends Controller
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    // Afficher tous les messages
    public function listeMessage()
    {
        // Récupère tous les messages
        $managerMessage = new MessageDAO($this->getPdo());
        $messagesListe = $managerMessage->findAll();

        // Génère la vue
        $template = $this->getTwig()->load('forum_detail.html.twig');
        echo $template->render(['messageListe' => $messagesListe]);
    }
}
?>
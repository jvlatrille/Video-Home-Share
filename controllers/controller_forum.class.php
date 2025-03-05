<?php

class ControllerForum extends Controller
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    // Afficher tous les forums
    public function listerForum()
    {
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
            // Récupère tous les forums
            $managerForum = new ForumDAO($this->getPdo());
            $forumsListe = $managerForum->findAll();

            $breadcrumb = [
                ['title' => 'Accueil', 'url' => 'index.php'],
                ['title' => 'Liste des forums', 'url' => 'index.php?controleur=forum&methode=listerForum']
            ];

            // Génère la vue
            $template = $this->getTwig()->load('forums.html.twig');
            echo $template->render(['forumListe' => $forumsListe, 'breadcrumb' => $breadcrumb]);
        } else {
            // Redirige vers la page de connexion
            header('Location: index.php?controleur=utilisateur&methode=connexion');
        }
    }
    //Fonction pour afficher un forum
    public function afficherForum()
    {
        $id = isset($_GET['idForum']) ? $_GET['idForum'] : null;

        //Recupere le forum
        $managerForum = new forumDAO($this->getPdo());
        $forumList = $managerForum->find($id);

        //Recupere les noms des forums
        $noms = $managerForum->afficherNomForum($idForum);

        //Generer la vue
        $template = $this->getTwig()->load('forum_detail.html.twig');

        echo $template->render(['forum' => $forumList, 'noms' => $noms]);
    }

    public function ajouterForum()
    {
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);

            // Récupère les données du forum depuis le formulaire
            $idForum = $_POST['id'] ?? null;
            $nom = $_POST['nom'] ?? $_GET['nom'] ?? null;
            $description = $_POST['description'] ?? $_GET['description'] ?? null;
            $theme = $_POST['theme'] ?? $_GET['theme'] ?? null;
            $contenu = $_POST['contenu'] ?? $_GET['contenu'] ?? null;
            $idUtilisateur = $utilisateurConnecte->getIdUtilisateur();
            $photoProfil = $utilisateurConnecte->getPhotoProfil();
            $pseudo = $utilisateurConnecte->getPseudo();

            //Ajoute le forum
            $managerForum = new ForumDao($this->getPdo());
            $forum = new Forum();
            $forum->setIdForum($idForum);
            $forum->setNom($nom);
            $forum->setDescription($description);
            $forum->setTheme($theme);
            $forum->setIdUtilisateur($idUtilisateur);
            $managerForum->creerForum($forum);
            
            //Récupère l'id du forum qui vient d'être créé
            $idForum = $forum->getIdForum();

            //Crée le 1er message du forum, obligatoire
            $managerMessage = new MessageDao($this->getPdo());
            $message = new Message();
            $message->setContenu($contenu);
            $message->setNbLikes(0);
            $message->setNbDislikes(0);
            $message->setPseudo($pseudo);
            $message->setPhotoProfil($photoProfil);
            $message->setIdUtilisateur($idUtilisateur);
            $message->setIdForum($idForum);
            $managerMessage->creerMessage($message);
            
            //Redirige vers la liste des forums
            header('Location: index.php?controleur=forum&methode=listerForum');
        }
    }
}

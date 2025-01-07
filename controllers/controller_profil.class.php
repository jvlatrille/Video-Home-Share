<?php

class ControllerProfil extends Controller{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }



    //Fonction pour afficher toutes les notif d'une personne
    public function listerNotif()
    {
        $id = isset($_GET['idNotif']) ? $_GET['idNotif'] : null;// Récupère l'ID de l'utilisateur depuis l'URL ou utilise une valeur par défaut
        
        if ($id === null) {
            // Si l'ID n'est pas fourni, utiliser un ID par défaut (par exemple l'ID de l'utilisateur connecté)
            $id = 1; // $_SESSION['idUtilisateur'] normalement
        }

        //Recupere les notifications
        $managerNotif=New NotificationDao($this->getPdo());
        $notifListe=$managerNotif->findAll($id);
        
        //Generer la vue avec les notifications de l'utilisateur
        $template = $this->getTwig()->load('profilNotifications.html.twig');
        echo $template->render(['notifListe' => $notifListe]);
            
    }
    


    //Fonction pour afficher une notification
    public function afficherNotif()
    {
        $id = isset($_GET['idNotif']) ? $_GET['idNotif'] : null;

        if ($id === null) {
            // Si l'ID n'est pas fourni, utiliser un ID par défaut (par exemple l'ID de l'utilisateur connecté)
            $id = 1; // $_SESSION['idUtilisateur'] normalement
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
        //Recupere l'id de la notification
        $idNotif = isset($_GET['idNotif']) ? $_GET['idNotif'] : null;
        $idUtilisateur = 1; //Id toujours 1 pour les tests mais normalement $_SESSION['idUtilisateur']
        
        //Supprime la notification
        $managerNotif = new NotificationDao($this->getPdo());
        $managerNotif->supprimerUneNotification($idNotif, $idUtilisateur);
        
        //Generer la vue
        $template = $this->getTwig()->load('profilNotifications.html.twig');

        //Redirige vers la liste des notifications
        header('Location: index.php?controleur=profil&methode=listerNotif&id=1');
    }

    
    //Fonction pour supprimer toutes les notifications d'une personne
    public function supprimerToutesLesNotifs()
    {
        //Recupere l'id de la notification
        $idUtilisateur = isset($_GET['idUtilisateur']) ? $_GET['idUtilisateur'] : null;
        //$idUtilisateur = 1; //Id toujours 1 pour les tests mais normalement $_SESSION['idUtilisateur']
        
        //Supprime la notification
        $managerNotif = new NotificationDao($this->getPdo());
        $managerNotif->supprimerToutesLesNotifs($idUtilisateur);
        
        //Generer la vue
        $template = $this->getTwig()->load('profilNotifications.html.twig');

        //Redirige vers la liste des notifications
        header('Location: index.php?controleur=profil&methode=listerNotif&id=1');
        

    }

    //Fonction pour afficher les informations de A Propos (Afficher tous les messages d'une personne)
    public function afficherAPropos()
    {
        //  // Vérifier si l'idUtilisateur est passé en paramètre dans l'URL
        // if (!isset($_GET['idUtilisateur']) || empty($_GET['idUtilisateur'])) {
        //     die("Paramètre idUtilisateur manquant !");
        // }

        // // Récupérer l'idUtilisateur depuis l'URL
        // $idUtilisateur = (int) $_GET['idUtilisateur'];

        $idUtilisateur = isset($_GET['idUtilisateur']) ? $_GET['idUtilisateur'] : null;

        // Récupérer tous les messages de cet utilisateur
        $managerMessage = new MessageDAO($this->getPdo());
        $messagesListe = $managerMessage->chargerAPropos($idUtilisateur);

        // // Récupérer les informations de l'utilisateur (si nécessaire)
        // $managerUtilisateur = new UtilisateurDao($this->getPdo());
        // $utilisateur = $managerUtilisateur->find($idUtilisateur);

        // Vérifier si l'utilisateur existe
        if (!$utilisateur) {
            die("Utilisateur introuvable !");
        }

        // Générer la vue avec les informations de l'utilisateur et ses messages
        $template = $this->getTwig()->load('profilAPropos.html.twig');
        echo $template->render([
            'utilisateur' => $utilisateur,  // Les informations de l'utilisateur
            'messageListe' => $messagesListe  // La liste des messages écrits par l'utilisateur
        ]);
    }
    
    
}
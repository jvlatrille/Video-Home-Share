<?php

class ControllerTestNotif extends Controller{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }



    //Fonction pour afficher toutes les notif d'une personne
    public function listerNotif()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;// Récupère l'ID de l'utilisateur depuis l'URL ou utilise une valeur par défaut
        
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
    
    

    //Fonction pour afficher le nombre total de notifications d'une personne
    public function nbNotif()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        
        //Recupere le nombre de notificationo pour cet utilisateur
        $managerNotif=New NotificationDao($this->getPdo());
        $nbNotif=$managerNotif->find($id);

        //Generer la vue
        $template = $this->getTwig()->load('profilNotifications.html.twig');
        
        echo $template->render(['nbNotif'=>$nbNotif]);

    }

    // //Fonction pour supprimer une notification
    // public function supprimerUneNotif()
    // {
    //     //Recupere l'id de la notification
    //     $idNotif = isset($_GET['id']) ? $_GET['id'] : null;
    //     $idUtilisateur = 1; //Id toujours 1 pour les tests mais normalement $_SESSION['idUtilisateur']
        
    //     //Supprime la watchlist
    //     $managerNotif = new NotificationDao($this->getPdo());
    //     $managerNotif->supprimerUneWatchlist($idNotif, $idUtilisateur);
        
    //     //Redirige vers la liste des watchlists
    //     header('Location: index.php?controleur=testNotif&methode=supprimerUneNotif'); //Id toujours 1 pour les tests mais normalement $_SESSION['idUtilisateur']
    // }

    // //Fonction pour supprimer toutes les notifications d'une personne
    // public function supprimerToutesLesNotifs()
    // {
    //     $id = isset($_GET['id']) ? $_GET['id'] : null;
        
    //     //Recupere les notifications
    //     $managerNotif=New NotificationDao($this->getPdo());
    //     $notif=$managerNotif->find($id);
    //     //Generer la vue
    //     $template = $this->getTwig()->load('profilNotifications.html.twig');
        
    //     echo $template->render(['notification'=>$notif]);

    // }
    
}
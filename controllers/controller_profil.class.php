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
    
}
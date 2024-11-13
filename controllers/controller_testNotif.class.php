<?php

class ControllerNotifier extends Controller{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    //Fonction pour afficher une notification d'un utilisateur
    public function find()
    {
        // Recupere toutes les watchlists
        $managerNotif = new NotificationDao($this->getPdo());
        $notifListe = $managerNotif->findAll(1); // normalement $_SESSION['idUtilisateur']
                                                         // mais pour les tests on met 1
        
        // Generer la vue
        $template = $this->getTwig()->load('profilNotifications.html.twig');
        
        echo $template->render(['notifListListe' => $notifListe]);
    }        

    //Fonction pour afficher toutes les notifications d'un utilisateur
    public function afficherNotifications()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        
        //Recupere la watchlist
        $managerNotif=New NotificationDao($this->getPdo());
        $notif=$managerNotif->find($id);
        //Generer la vue
        $template = $this->getTwig()->load('profilNotifications.html.twig');
        
        echo $template->render(['notification'=>$notif]);

    }


    
}
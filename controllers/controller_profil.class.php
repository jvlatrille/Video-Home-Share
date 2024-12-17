<?php

class ControllerProfil extends Controller{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }



    //Fonction pour afficher toutes les notif d'une personne
    public function listerNotif()
    {
        // Vérifie si un utilisateur est connecté
        if (isset($_SESSION['utilisateur'])) {
            $utilisateurConnecte = unserialize($_SESSION['utilisateur']);


            //Recupere les notifications
            $managerNotif=New NotificationDao($this->getPdo());
            $notifListe=$managerNotif->findAll($utilisateurConnecte->getIdUtilisateur());
            
            //Generer la vue avec les notifications de l'utilisateur
            $template = $this->getTwig()->load('profilNotifications.html.twig');
            echo $template->render(['notifListe' => $notifListe]);
            
    }
    
}

    //Fonction pour afficher une notification
    public function afficherNotif()
    {
        $id = isset($_GET['idNotif']) ? $_GET['idNotif'] : null;

        if ($id === null) {
            $template = $this->getTwig()->load('profilNotifications.html.twig');
            echo $template->render();

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
        // Vérifie si un utilisateur est connecté
    if (isset($_SESSION['utilisateur'])) {
        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);

        $idUtilisateur = $utilisateurConnecte->getIdUtilisateur();
        //Recupere l'id de la notification
        $idNotif = isset($_GET['idNotif']) ? $_GET['idNotif'] : null;
        
        //Supprime la notification
        $managerNotif = new NotificationDao($this->getPdo());
        $managerNotif->supprimerUneNotification($idNotif, $idUtilisateur);
        
        //Generer la vue
        $template = $this->getTwig()->load('profilNotifications.html.twig');

        //Redirige vers la liste des notifications
        header('Location: index.php?controleur=profil&methode=listerNotif&id='.$idUtilisateur.'');
    }

}
    //Fonction pour supprimer toutes les notifications d'une personne
    public function supprimerToutesLesNotifs()
    {
        // Vérifie si un utilisateur est connecté
    if (isset($_SESSION['utilisateur'])) {
        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        //Recupere l'id de la notification
        $idUtilisateur = $utilisateurConnecte->getIdUtilisateur();
        
        //Supprime la notification
        $managerNotif = new NotificationDao($this->getPdo());
        $managerNotif->supprimerToutesLesNotifs($idUtilisateur);
        
        //Generer la vue
        $template = $this->getTwig()->load('profilNotifications.html.twig');

        //Redirige vers la liste des notifications
        header('Location: index.php?controleur=profil&methode=listerNotif&id='.$idUtilisateur.'');
    }

    }
    
}
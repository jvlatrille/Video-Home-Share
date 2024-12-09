<?php

class ControllerUtilisateur extends Controller
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    // Afficher tous les utilisateurs
    public function AllUtilisateurs()
    {
        // Récupère tout les utilisateurs
        $managerUtilisateur = new UtilisateurDAO($this->getPdo());
        $utilisateurListe = $managerUtilisateur->findAll();

        // Génère la vue
        $template = $this->getTwig()->load('utilisateur.html.twig');
        echo $template->render(['utilisateurListe' => $utilisateurListe]);
    }

    // Afficher un seul utilisateur
    public function afficherUtilisateur()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        // Récupère l'utilisateur
        $managerUtilisateur = new UtilisateurDAO($this->getPdo());
        $utilisateur = $managerUtilisateur->find($id);

        // Génère la vue
        $template = $this->getTwig()->load('utilisateur_detail.html.twig');
        echo $template->render(['utilisateur' => $utilisateur]);
    }

    // Changer de pseudo
    public function changerPseudo() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $newPseudo = isset($_GET['pseudo']) ? $_GET['pseudo'] : null;
    
        if (!$id || !$newPseudo) {
            throw new Exception('Informations manquantes : ID ou pseudo.');
        }
        
        $managerUtilisateur = new UtilisateurDao($this->getPdo());
        $reussite = $managerUtilisateur->changerPseudo($id, $newPseudo);
    
        $message = $reussite ? "Le pseudo a été changé avec succès." : "Erreur lors du changement de pseudo.";
        $utilisateur = $managerUtilisateur->find($id);
    
        $template = $this->getTwig()->load('utilisateur_detail.html.twig');
        echo $template->render([
            'utilisateur' => $utilisateur,
            'message' => $message
        ]);
    }    
    
    // Changer de Mail
    public function changerMail() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $newMail = isset($_GET['mail']) ? $_GET['mail'] : null;
    
        if (!$id || !$newMail) {
            throw new Exception('Informations manquantes : ID ou mail.');
        }
        
        $managerUtilisateur = new UtilisateurDao($this->getPdo());
        $reussite = $managerUtilisateur->changerMail($id, $newMail);
    
        $message = $reussite ? "Le mail a été changé avec succès." : "Erreur lors du changement de mail.";
        $utilisateur = $managerUtilisateur->find($id);
    
        $template = $this->getTwig()->load('utilisateur_detail.html.twig');
        echo $template->render([
            'utilisateur' => $utilisateur,
            'message' => $message
        ]);
    }
    
    /**
     * @brief Affiche le formulaire de connexion d'un utilisateur
     * @author Thibault CHIPY 
     *
     * @return void
     */
    public function connexion(){
        $template = $this->getTwig()->load('connexion.html.twig');
        echo $template->render();
    }

    /**
     * @brief Affiche le formulaire d'inscription d'un utilisateur
     * @author Thibault CHIPY 
     * 
     * @return void
     */

    public function inscription(){
        $template = $this->getTwig()->load('inscription.html.twig');
        echo $template->render();
    }
    
    /**
     * @brief Vérifie la connexion d'un utilisateur
     * @author Thibault Chipy 
     * @version 1.0
     * 
     * @return void
     */
    public function verifConnexion(){
        $mail=isset($_POST['mail'])?$_POST['mail']:null;
        $mdp=isset($_POST['mdp'])?$_POST['mdp']:null;

        $mail = str_replace(' ', '', $mail); // On enlève les espaces

        $managerUtilisateur = new UtilisateurDao($this->getPdo());
        $utilisateur = $managerUtilisateur->findByMail($mail);

        if($utilisateur && password_verify($mdp, $utilisateur->getMotDePasse())){
            $_SESSION['idUtilisateur'] = $utilisateur->getIdUtilisateur();
            $_SESSION['pseudo'] = $utilisateur->getPseudo();
            $_SESSION['role'] = $utilisateur->getRole();

            $this->show();
            header('Location: index.php');
        }else{
            $template = $this->getTwig()->load('connexion.html.twig');
            echo $template->render();
        }
    }
}
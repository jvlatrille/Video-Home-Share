<?php

class ControllerUtilisateur extends Controller
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
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
     * @version 2.0
     * 
     * @return void
     */
    public function verifConnexion(){
        $mail=isset($_POST['mail'])?$_POST['mail']:null;
        $mdp=isset($_POST['mdp'])?$_POST['mdp']:null;

        // $mail = str_replace(' ', '', $mail); // On enlève les espaces
        $managerUtilisateur = new UtilisateurDao($this->getPdo());
        $utilisateur = $managerUtilisateur->findByMail($mail);
        if($utilisateur && password_verify($mdp, $utilisateur->getMotDePasse())){

            $_SESSION['utilisateur'] = serialize($utilisateur);
            $this->getTwig()->addGlobal('utilisateurConnecte', $utilisateur);
            
            $managerOA = new OADao($this->getPdo());
            $oaListe = $managerOA->findMeilleurNote();
            $template = $this->getTwig()->load('index.html.twig');
            echo $template->render(['oaListe' => $oaListe]);
  

        }else{  
            $template = $this->getTwig()->load('connexion.html.twig');
            echo $template->render(['message' => 'Identifiants incorrects']);
        }
     
    }

    /**
     * @brief Vérifie l'inscription d'un utilisateur et l'ajoute à la base de données si tout est correcte
     * @author Thibault CHIPY
     * 
     * @return void
     */

// MANQUE LES VERIF DES FORMULAIRES AVEC FONCTION : A FAIRE      
    public function verifInscription(){
        $idUtilisateur=isset($_POST['idUtilisateur'])?$_POST['idUtilisateur']:null;
        $pseudo=isset($_POST['pseudo'])?$_POST['pseudo']:null;
        $photoProfil=isset($_POST['photoProfil'])?$_POST['photoProfil']:null;
        $banniereProfil=isset($_POST['banniereProfil'])?$_POST['banniereProfil']:null;
        $dateNaiss=isset($_POST['dateNaiss'])?$_POST['dateNaiss']:null;
        $mail=isset($_POST['mail'])?$_POST['mail']:null;
        $mdp=isset($_POST['mdp'])?$_POST['mdp']:null;
        $mdpVerif=isset($_POST['mdpVerif'])?$_POST['mdpVerif']:null;
        $role=isset($_POST['role'])?$_POST['role']:'utilisateur'; // Role par défaut : utilisateur
        // $mail = str_replace(' ', '', $mail); // On enlève les espaces

        
        //Vérifier l'age de l'utilisateur
        $dateJour = date('Y-m-d');
        
        //Comparé la date du jour avec la date de naissance de l'utilisateur
        $dateNaiss = new DateTime($dateNaiss);
        $dateJour = new DateTime($dateJour);
        $age = $dateNaiss->diff($dateJour);
        $age = $age->format('%y');

        $verifPassee=true;

        //Si l'utilisateur a moins de 13 ans
        if($age < 13){
            $template = $this->getTwig()->load('inscription.html.twig');
            echo $template->render(['message' => 'Vous devez avoir au moins
            13 ans pour vous inscrire']);
        }
        

        $managerUtilisateur = new UtilisateurDao($this->getPdo());
        $utilisateur = $managerUtilisateur->emailExiste($mail);
        $verifMdp=$managerUtilisateur->estRobuste($mdp);
        
        // Vérifie si l'email existe déjà
        if($utilisateur){
            $template = $this->getTwig()->load('inscription.html.twig');
            echo $template->render(['message' => 'L\'adresse mail est déjà utilisée']);
            $verifPassee=false;
            return;
        }
        
        if($mdp != $mdpVerif){
            $template = $this->getTwig()->load('inscription.html.twig');
            echo $template->render(['message' => 'Les mots de passe ne correspondent pas']);
            $verifPassee=false;
            return;
        }
        if(!$verifMdp){
            $template = $this->getTwig()->load('inscription.html.twig');
            echo $template->render(['message' => 'Le mot de passe n\'est pas assez robuste']);
            $verifPassee=false;
            return;
        }

        if($verifPassee){
        $mdp = password_hash($mdp, PASSWORD_BCRYPT); // On hash le mot de passe avec BCRYPT
        $utilisateur = new Utilisateur($idUtilisateur,$pseudo, $photoProfil, $banniereProfil, $mail, $mdp, $role); // Role par défaut : utilisateur
        $utilisateur->setIdUtilisateur($this->getPdo()->lastInsertId());
        $managerUtilisateur->creerUtilisateur($utilisateur);
        }
        else{
            $template = $this->getTwig()->load('inscription.html.twig');
            echo $template->render(['message' => 'Erreur lors de l\'inscription']);
        }
        header('Location: index.php?controleur=utilisateur&methode=connexion');
        }

        /**
         * @brief Déconnecte un utilisateur et le redirige vers la page d'accueil
         * @details Détruit la session de l'utilisateur et le redirige vers la page d'accueil
         * 
         * @return void
         */
        public function deconnexion(){
            session_destroy();
            header('Location: index.php');
        }
}

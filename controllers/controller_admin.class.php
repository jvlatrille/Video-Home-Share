<?php
/**
 * @file controller_admin.class.php
 * @author VINET LATRILLE Jules
 * @brief Contrôleur pour la gestion des utilisateurs administrateurs
 * @details Ce contrôleur gère l'affichage et la gestion des utilisateurs administrateurs.
 * @version 1.0
 * @date 2025-01-11
 */
class ControllerAdmin extends Controller
{
    private UtilisateurDao $utilisateurDao;

    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
        $this->utilisateurDao = new UtilisateurDao($this->getPdo());
    }

    public function render()
    {
        // Vérifie si un utilisateur est connecté
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controleur=utilisateur&methode=connexion');
            exit();
        }

        // Récupération de l'utilisateur connecté
        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);

        // Vérifie si l'utilisateur a le rôle 'admin'
        if ($utilisateurConnecte->getRole() !== 'admin') {
            header('Location: index.php');
            exit();
        }

        // Récupérer la liste des utilisateurs
        $utilisateurListe = $this->utilisateurDao->findAll();

        // Rendu de la page admin
        $template = $this->getTwig()->load('admin.html.twig');
        echo $template->render([
            'utilisateur' => $utilisateurConnecte,
            'utilisateurListe' => $utilisateurListe
        ]);
    }
}

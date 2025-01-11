<?php

class ControllerAdmin extends Controller
{
    private AdminDao $adminDao;

    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
        $this->adminDao = new AdminDao($this->getPdo());
    }

    public function render()
    {
        $this->verifierAdmin();

        $utilisateurListe = $this->adminDao->getAllUtilisateurs();

        // ✅ Utilisation de la méthode getTwig() au lieu de $this->twig
        echo $this->getTwig()->render('admin.html.twig', [
            'utilisateurListe' => $utilisateurListe
        ]);
    }

    private function verifierAdmin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?controleur=utilisateur&methode=connexion');
            exit();
        }

        $utilisateurConnecte = unserialize($_SESSION['utilisateur']);
        if ($utilisateurConnecte->getRole() !== 'admin') {
            header('Location: index.php');
            exit();
        }
    }
}

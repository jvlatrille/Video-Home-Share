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
    private AdminDao $adminDao;

    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
        $this->adminDao = new AdminDao($this->getPdo());
    }

    public function adminModifierUtilisateur()
    {
        $this->verifierAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idUtilisateur = $_POST['idUtilisateur'] ?? null;
            $pseudo = $_POST['pseudo'] ?? '';
            $photoProfil = $_POST['photoProfil'] ?? 'default.png';
            $banniereProfil = $_POST['banniereProfil'] ?? 'default.png';
            $adressMail = $_POST['adressMail'] ?? '';
            $motDePasse = $_POST['motDePasse'] ?? '';
            $role = $_POST['role'] ?? 'user';

            // Validation des données
            $reglesValidation = [
                'pseudo' => ['obligatoire' => true, 'type' => 'string', 'longueur_min' => 3],
                'adressMail' => ['obligatoire' => true, 'format' => FILTER_VALIDATE_EMAIL],
                'motDePasse' => ['obligatoire' => true, 'longueur_min' => 8],
                'role' => ['obligatoire' => true, 'type' => 'string']
            ];

            $validator = new Validator($reglesValidation);

            if ($validator->valider($_POST)) {
                $this->adminDao->adminModifierUtilisateur(
                    $idUtilisateur,
                    $pseudo,
                    $photoProfil,
                    $banniereProfil,
                    $adressMail,
                    $motDePasse,
                    $role
                );
            }

            header('Location: index.php?controleur=admin&methode=render');
            exit();
        }
    }

    private function verifierAdmin()
    {
        session_start();

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

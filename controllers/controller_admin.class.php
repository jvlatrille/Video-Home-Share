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

        echo $this->getTwig()->render('admin.html.twig', [
            'utilisateurListe' => $utilisateurListe
        ]);
    }

    public function adminModifierUtilisateur()
    {
        $this->verifierAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idUtilisateur = $_POST['idUtilisateur'];
            $pseudo = $_POST['pseudo'];
            $adressMail = $_POST['adressMail'];
            $motDePasse = $_POST['motDePasse'];
            $role = $_POST['role'];

            // Récupérer les informations actuelles de l'utilisateur
            $utilisateurActuel = $this->adminDao->getUtilisateurById($idUtilisateur);

            if (!$utilisateurActuel) {
                header('Location: index.php?controleur=admin&methode=render&error=user_not_found');
                exit();
            }

            // Si le champ est vide, on conserve l'ancienne valeur
            $photoProfil = $this->uploadImage('photoProfil', $utilisateurActuel->getPhotoProfil());
            $banniereProfil = $this->uploadImage('banniereProfil', $utilisateurActuel->getBanniereProfil());

            // Si le mot de passe est vide, on ne le modifie pas
            $motDePasseFinal = !empty($motDePasse) ? password_hash($motDePasse, PASSWORD_BCRYPT) : null;

            $resultat = $this->adminDao->adminModifierUtilisateur(
                $idUtilisateur,
                $pseudo,
                $photoProfil,
                $banniereProfil,
                $adressMail,
                $motDePasseFinal,
                $role
            );

            if ($resultat) {
                header('Location: index.php?controleur=admin&methode=render&success=1');
            } else {
                header('Location: index.php?controleur=admin&methode=render&error=1');
            }
            exit();
        }
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

    private function uploadImage($inputName, $default)
    {
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
            $targetDir = 'img/profils/';
            $fileName = uniqid() . '_' . basename($_FILES[$inputName]['name']);
            $targetFilePath = $targetDir . $fileName;

            // Vérifie le type d'image
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array(strtolower($fileType), $allowedTypes)) {
                move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetFilePath);
                return $fileName;
            }
        }

        return $default;
    }

    public function supprimerUtilisateur()
    {
        $this->verifierAdmin();

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $idUtilisateur = (int)$_GET['id'];

            // Vérifier si l'utilisateur existe avant suppression
            $utilisateur = $this->adminDao->getUtilisateurById($idUtilisateur);
            if ($utilisateur) {
                $resultat = $this->adminDao->supprimerUtilisateur($idUtilisateur);

                if ($resultat) {
                    header('Location: index.php?controleur=admin&methode=render&success=suppression');
                } else {
                    header('Location: index.php?controleur=admin&methode=render&error=suppression_failed');
                }
            } else {
                header('Location: index.php?controleur=admin&methode=render&error=user_not_found');
            }
        } else {
            header('Location: index.php?controleur=admin&methode=render&error=invalid_id');
        }
        exit();
    }
}

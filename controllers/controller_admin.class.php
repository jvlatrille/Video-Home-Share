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

            // Upload des images avec le bon format : id_pseudo.extension
            $photoProfil = $this->uploadImage('photoProfil', $utilisateurActuel->getPhotoProfil(), $idUtilisateur, $pseudo, 'profil');
            $banniereProfil = $this->uploadImage('banniereProfil', $utilisateurActuel->getBanniereProfil(), $idUtilisateur, $pseudo, 'banniere');

            // Si le mot de passe est vide, on ne le modifie pas
            $motDePasseFinal = !empty($motDePasse) ? password_hash($motDePasse, PASSWORD_BCRYPT) : null;

            // Mise à jour des données dans la BD
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

    private function uploadImage($inputName, $default, $idUtilisateur, $pseudo, $type = 'profil')
    {
        // Dossier de destination selon le type
        $targetDir = $type === 'banniere' ? 'img/banniere/' : 'img/profils/';

        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
            // Extension du fichier
            $fileType = pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION);
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array(strtolower($fileType), $allowedTypes)) {
                // Nettoyer le pseudo
                $pseudoNettoye = preg_replace('/[^a-zA-Z0-9_-]/', '', strtolower($pseudo));

                // Nouveau nom : id_pseudo.extension
                $fileName = $idUtilisateur . '_' . $pseudoNettoye . '.' . $fileType;
                $targetFilePath = $targetDir . $fileName;

                // Supprimer l'ancienne image si elle existe
                if (file_exists($targetFilePath)) {
                    unlink($targetFilePath);
                }

                // Déplacer la nouvelle image
                if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetFilePath)) {
                    return $fileName;
                }
            }
        }

        // Retourner l'ancienne image si aucun upload n'a été fait
        return $default;
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

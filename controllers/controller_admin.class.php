<?php

/**
 * @file controller_admin.class.php
 * @author VINET LATRILLE Jules
 * @brief Gère les opérations d'administration liées aux utilisateurs dans la base de données.
 * @version 1.0
 * @date 2025-01-11
 */
class ControllerAdmin extends Controller
{
    /** @var AdminDao $adminDao Instance de la classe AdminDao pour la gestion des utilisateurs. */
    private AdminDao $adminDao;

    /**
     * @brief Constructeur du contrôleur d'administration.
     * @param \Twig\Environment $twig Moteur de templates Twig.
     * @param \Twig\Loader\FilesystemLoader $loader Chargeur de templates Twig.
     */
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
        $this->adminDao = new AdminDao($this->getPdo());
    }

    /**
     * @brief Affiche la page d'administration avec la liste des utilisateurs.
     */
    public function render()
    {
        $this->verifierAdmin();
        $utilisateurListe = $this->adminDao->getAllUtilisateurs();

        echo $this->getTwig()->render('admin.html.twig', [
            'utilisateurListe' => $utilisateurListe
        ]);
    }

    /**
     * @brief Permet à l'administrateur de modifier les informations d'un utilisateur.
     *
     * Cette méthode met à jour les données d'un utilisateur et gère l'upload des images.
     */
    public function adminModifierUtilisateur()
    {
        $this->verifierAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            ob_start();  // ➡️ Mise en tampon pour éviter les erreurs de header

            $idUtilisateur = $_POST['idUtilisateur'];
            $pseudo = $_POST['pseudo'];
            $adressMail = $_POST['adressMail'];
            $motDePasse = $_POST['motDePasse'];
            $role = $_POST['role'];

            $utilisateurActuel = $this->adminDao->getUtilisateurById($idUtilisateur);

            if (!$utilisateurActuel) {
                header('Location: index.php?controleur=admin&methode=render&error=user_not_found');
                ob_end_flush();
                exit();
            }

            $photoProfil = $this->uploadImage('photoProfil', $utilisateurActuel->getPhotoProfil(), $idUtilisateur, $pseudo, 'profil');
            $banniereProfil = $this->uploadImage('banniereProfil', $utilisateurActuel->getBanniereProfil(), $idUtilisateur, $pseudo, 'banniere');

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

            ob_end_flush();  // ➡️ Fin de la mise en tampon
            exit();
        }
    }


    /**
     * @brief Gère l'upload d'une image de profil ou de bannière.
     * 
     * @param string $inputName Nom de l'input du formulaire.
     * @param string $default Valeur par défaut si aucun fichier n'est uploadé.
     * @param int $idUtilisateur ID de l'utilisateur.
     * @param string $pseudo Pseudo de l'utilisateur.
     * @param string $type Type d'image ('profil' ou 'banniere').
     * @return string Nom du fichier uploadé ou la valeur par défaut.
     */
    private function uploadImage($inputName, $default, $idUtilisateur, $pseudo, $type = 'profil')
    {
        $targetDir = $type === 'banniere' ? 'img/banniere/' : 'img/profils/';

        // Vérifie si le dossier existe, sinon le crée
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
            $fileType = pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION);
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array(strtolower($fileType), $allowedTypes)) {
                $pseudoNettoye = preg_replace('/[^a-zA-Z0-9_-]/', '', strtolower($pseudo));
                $fileName = $idUtilisateur . '_' . $pseudoNettoye . '.' . $fileType;
                $targetFilePath = $targetDir . $fileName;

                // Supprime l'ancienne image si elle existe
                if (file_exists($targetFilePath)) {
                    unlink($targetFilePath);
                }

                // Déplace le fichier et vérifie si ça fonctionne
                if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetFilePath)) {
                    return $fileName;
                } else {
                    error_log("Erreur lors du déplacement du fichier : " . $_FILES[$inputName]['error']);
                }
            }
        }

        return $default;
    }



    /**
     * @brief Vérifie si l'utilisateur connecté est un administrateur.
     *
     * Redirige vers la page de connexion si l'utilisateur n'est pas admin.
     */
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

    /**
     * @brief Supprime un utilisateur de la base de données.
     *
     * @details Vérifie l'existence de l'utilisateur avant de le supprimer.
     */
    public function supprimerUtilisateur()
    {
        $this->verifierAdmin();

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $idUtilisateur = (int) $_GET['id'];

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

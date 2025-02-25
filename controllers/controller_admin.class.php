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
    private UtilisateurDao $utilisateurDao;

    /**
     * @brief Constructeur du contrôleur d'administration.
     * @param \Twig\Environment $twig Moteur de templates Twig.
     * @param \Twig\Loader\FilesystemLoader $loader Chargeur de templates Twig.
     */
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
        $this->adminDao = new AdminDao($this->getPdo());
        $this->utilisateurDao = new UtilisateurDao($this->getPdo());
    }

    /**
     * @brief Affiche la page d'administration avec la liste des utilisateurs.
     */
    public function render()
    {
        $this->verifierAdmin();
        $utilisateurListe = $this->utilisateurDao->findAll();
        $quizzDao = new QuizzDao($this->getPdo());
        $quizzListe = $quizzDao->findAll();

        // Récupérer les questions pour chaque quiz
        $questionDao = new QuestionDao($this->getPdo());
        $detailedQuestions = [];

        foreach ($quizzListe as $quiz) {
            $detailedQuestions[$quiz->getIdQuizz()] = $questionDao->findAll($quiz->getIdQuizz());
        }

        echo $this->getTwig()->render('admin.html.twig', [
            'utilisateurListe'  => $utilisateurListe,
            'quizzListe'        => $quizzListe,
            'detailedQuestions' => $detailedQuestions
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
            ob_start();

            $idUtilisateur = $_POST['idUtilisateur'];
            $pseudo        = $_POST['pseudo'];
            $role          = $_POST['role'];

            $utilisateurActuel = $this->utilisateurDao->find($idUtilisateur);
            if (!$utilisateurActuel) {
                header('Location: index.php?controleur=admin&methode=render&error=user_not_found');
                ob_end_flush();
                exit();    {
                    $this->verifierAdmin();
            
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        ob_start();
            
                        $idUtilisateur = $_POST['idUtilisateur'];
                        $pseudo        = $_POST['pseudo'];
                        $role          = $_POST['role'];
            
                        $utilisateurActuel = $this->utilisateurDao->find($idUtilisateur);
                        if (!$utilisateurActuel) {
                            header('Location: index.php?controleur=admin&methode=render&error=user_not_found');
                            ob_end_flush();
                            exit();
                        }
            
                        // Démarrage de la транзакция (tranzaktsiya: transaction)
                        $pdo = $this->getPdo();
                        $pdo->beginTransaction();
            
                        try {
                            // Gérer l'upload de la photo de profil
                            $photoProfil = $this->uploadImage(
                                'photoProfil',
                                $_POST['currentPhotoProfil'],
                                $idUtilisateur,
                                $pseudo,
                                'profil'
                            );
            
                            // Gérer l'upload de la bannière de profil
                            $banniereProfil = $this->uploadImage(
                                'banniereProfil',
                                $_POST['currentBanniereProfil'],
                                $idUtilisateur,
                                $pseudo,
                                'banniere'
                            );
            
                            // Mise à jour du pseudo
                            $okPseudo = $this->utilisateurDao->changerPseudo($idUtilisateur, $pseudo);
            
                            // Mettre à jour la photo si elle a changé
                            $okPhoto = true;
                            if ($photoProfil !== $utilisateurActuel->getPhotoProfil()) {
                                $okPhoto = $this->utilisateurDao->updateUserPhoto($idUtilisateur, $photoProfil);
                            }
            
                            // Mettre à jour la bannière si elle a changé
                            $okBanniere = true;
                            if ($banniereProfil !== $utilisateurActuel->getBanniereProfil()) {
                                $okBanniere = $this->utilisateurDao->updateUserBanniere($idUtilisateur, $banniereProfil);
                            }
            
                            // Changer le rôle
                            $okRole = $this->utilisateurDao->changerRole($idUtilisateur, $role);
            
                            // Vérifier que toutes les mises à jour se sont bien déroulées
                            if ($okPseudo && $okPhoto && $okBanniere && $okRole) {
                                $pdo->commit();
                                header('Location: index.php?controleur=admin&methode=render&success=1');
                            } else {
                                $pdo->rollBack();
                                header('Location: index.php?controleur=admin&methode=render&error=1');
                            }
                        } catch (Exception $e) {
                            $pdo->rollBack();
                            error_log("Erreur lors de la mise à jour utilisateur : " . $e->getMessage());
                            header('Location: index.php?controleur=admin&methode=render&error=exception');
                        }
            
                        ob_end_flush();
                        exit();
                    }
                }
            }

            // Démarrage de la транзакция (tranzaktsiya: transaction)
            $pdo = $this->getPdo();
            $pdo->beginTransaction();

            try {
                // Gérer l'upload de la photo de profil
                $photoProfil = $this->uploadImage(
                    'photoProfil',
                    $_POST['currentPhotoProfil'],
                    $idUtilisateur,
                    $pseudo,
                    'profil'
                );

                // Gérer l'upload de la bannière de profil
                $banniereProfil = $this->uploadImage(
                    'banniereProfil',
                    $_POST['currentBanniereProfil'],
                    $idUtilisateur,
                    $pseudo,
                    'banniere'
                );

                // Mise à jour du pseudo
                $okPseudo = $this->utilisateurDao->changerPseudo($idUtilisateur, $pseudo);

                // Mettre à jour la photo si elle a changé
                $okPhoto = true;
                if ($photoProfil !== $utilisateurActuel->getPhotoProfil()) {
                    $okPhoto = $this->utilisateurDao->updateUserPhoto($idUtilisateur, $photoProfil);
                }

                // Mettre à jour la bannière si elle a changé
                $okBanniere = true;
                if ($banniereProfil !== $utilisateurActuel->getBanniereProfil()) {
                    $okBanniere = $this->utilisateurDao->updateUserBanniere($idUtilisateur, $banniereProfil);
                }

                // Changer le rôle
                $okRole = $this->utilisateurDao->changerRole($idUtilisateur, $role);

                // Vérifier que toutes les mises à jour se sont bien déroulées
                if ($okPseudo && $okPhoto && $okBanniere && $okRole) {
                    $pdo->commit();
                    header('Location: index.php?controleur=admin&methode=render&success=1');
                } else {
                    $pdo->rollBack();
                    header('Location: index.php?controleur=admin&methode=render&error=1');
                }
            } catch (Exception $e) {
                $pdo->rollBack();
                error_log("Erreur lors de la mise à jour utilisateur : " . $e->getMessage());
                header('Location: index.php?controleur=admin&methode=render&error=exception');
            }

            ob_end_flush();
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

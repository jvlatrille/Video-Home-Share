<?php

class Validator
{
    private array $regles; // Les règles de validation à vérifier
    private array $messagesErreurs = []; // Les messages d'erreurs

    
    public function __construct(array $regles)
    {
        $this->regles = $regles;
    }
    
    public function getMessagesErreurs(): array
    {
        return $this->messagesErreurs;
    }

    // Gere la validation des champs
    public function valider(array $donnees): bool
    {
        $valide = true;
        $this->messagesErreurs = []; // Réinitialisation des erreurs à chaque validation

        foreach ($this->regles as $champ => $reglesChamp)
        {
            $valeur = $donnees[$champ] ?? null;

            if (!$this->validerChamp($champ, $valeur, $reglesChamp))
            {
                $valide = false;
            }
        }

        if($valide === true)
        {
            $this->messagesErreurs[] = "Les changements ont été effectués.";
        }
        else
        {
            $this->messagesErreurs[] = "Veuillez rectifier les erreurs.";
        }

        return $valide;
    }

    // Valide un seul champ
    private function validerChamp(string $champ, mixed $valeur, array $regles): bool
    {
        $estValide = true;

        // 1. Vérification de la règle "obligatoire" avant toute autre validation.
        if (isset($regles['obligatoire']) && $regles['obligatoire'] && empty($valeur))
        {
            $this->messagesErreurs[] = "Le champ $champ est obligatoire.";
            return false; // Arrêter ici si le champ est obligatoire et vide
        }

        // 2. Si le champ est vide et non obligatoire, aucune autre validation n'est nécessaire
        if (empty($valeur) && (!isset($regles['obligatoire']) || !$regles['obligatoire']))
        {
            return true;
        }

        // Validation des autres règles pour les champs non vides ou obligatoires remplis.
        foreach ($regles as $regle => $parametre)
        {
            switch ($regle)
            {
                case 'type':
                    if ($parametre === 'string' && !is_string($valeur))
                    {
                        $this->messagesErreurs[] = "Le champ $champ doit être une chaîne de caractères.";
                        $estValide = false;
                    }
                    elseif ($parametre === 'integer' && !filter_var($valeur, FILTER_VALIDATE_INT))
                    {
                        $this->messagesErreurs[] = "Le champ $champ doit être un nombre entier.";
                        $estValide = false;
                    }
                    elseif ($parametre === 'numeric' && !is_numeric($valeur))
                    {
                        $this->messagesErreurs[] = "Le champ $champ doit être une valeur numérique.";
                        $estValide = false;
                    }
                    break;
                case 'longueur_min':
                    if (strlen($valeur) < $parametre)
                    {
                        $this->messagesErreurs[] = "Le champ $champ doit comporter au moins $parametre caractères.";
                        $estValide = false;
                    }
                    break;
                case 'longueur_max':
                    if (strlen($valeur) > $parametre)
                    {
                        $this->messagesErreurs[] = "Le champ $champ ne doit pas dépasser $parametre caractères.";
                        $estValide = false;
                    }
                    break;
                case 'longueur_exacte':
                    if (strlen($valeur) !== $parametre)
                    {
                        $this->messagesErreurs[] = "Le champ $champ doit comporter exactement $parametre caractères.";
                        $estValide = false;
                    }
                    break;
                case 'format':
                    if (is_string($parametre) && !preg_match($parametre, $valeur))
                    {
                        $this->messagesErreurs[] = "Le format du champ $champ est invalide.";
                        $estValide = false;
                    }
                    elseif ($parametre === FILTER_VALIDATE_EMAIL && !filter_var($valeur, FILTER_VALIDATE_EMAIL))
                    {
                        $this->messagesErreurs[] = "L'adresse email est invalide.";
                        $estValide = false;
                    }
                    elseif ($parametre === FILTER_VALIDATE_URL && !filter_var($valeur, FILTER_VALIDATE_URL))
                    {
                        $this->messagesErreurs[] = "L'URL du site web est invalide.";
                        $estValide = false;
                    }
                    break;
                case 'plage_min':
                    if ($valeur < $parametre)
                    {
                        $this->messagesErreurs[] = "La valeur de $champ doit être au minimum $parametre.";
                        $estValide = false;
                    }
                    break;
                case 'plage_max':
                    if ($valeur > $parametre)
                    {
                        $this->messagesErreurs[] = "La valeur de $champ ne doit pas dépasser $parametre.";
                        $estValide = false;
                    }
                    break;
            }
        }

        return $estValide;
    }

    // Verifie la validité du fichier
    public function validerPhotoProfil(array $photo, array &$messagesErreurs): bool
    {
        $valide = true;

        // 1. Champs obligatoires : la photo de profil est facultative
        if ($photo['error'] === UPLOAD_ERR_NO_FILE) {
            return true;  // Si aucun fichier n'est envoyé, c'est valide.
        }

        // 6. Vérification du type et de la taille du fichier
        $typesAutorises = ['image/jpeg', 'image/png']; // Formats autorisés
        $tailleMaxAutoriseeEnOctets = 2 * 1024 * 1024; // 2 Mo max

        $typeMimeReel = mime_content_type($photo['tmp_name']); // Obtenir le type MIME réel du fichier
        if (!in_array($typeMimeReel, $typesAutorises)) {
            $messagesErreurs[] = "Le fichier doit être au format JPG ou PNG.";
            $valide = false;
        }

        if ($photo['size'] > $tailleMaxAutoriseeEnOctets) {
            $messagesErreurs[] = "Le fichier ne doit pas dépasser 2 Mo.";
            $valide = false;
        }

        // Vérification des dimensions du fichier image
        $dimensions = getimagesize($photo['tmp_name']);
        if ($dimensions === false) {
            $messagesErreurs[] = "Le fichier doit être une image valide.";
            $valide = false;
        }

        return $valide;
    }

    // Verifie l'upload de fichier
    public function validerUploadEtPhoto(array $fichier, array &$messagesErreurs): bool
    {
        if (isset($fichier) && $fichier['error'] === UPLOAD_ERR_OK) {
            // Valider la photo
            return $this->validerPhotoProfil($fichier, $messagesErreurs);
        } else {
            // Gestion des erreurs d'upload
            switch ($fichier['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $messagesErreurs[] = "Le fichier dépasse la taille maximale autorisée sur le serveur.";
                    return false;
                case UPLOAD_ERR_PARTIAL:
                    $messagesErreurs[] = "Le fichier n'a été que partiellement téléchargé.";
                    return false;
                case UPLOAD_ERR_NO_FILE:
                    $messagesErreurs[] = "Aucun fichier n'a été téléchargé.";
                    return false;
                default:
                    $messagesErreurs[] = "Erreur lors du téléchargement du fichier.";
                    return false;
            }
        }
    }
    
}
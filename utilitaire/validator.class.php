<?php 

/**
 * @file validator.class.php 
 * @brief Classe de validation des données des formulaires
 * @details Cette classe permet de valider les données des formulaires en fonction des règles de validation définies
 * @version 1.0
 * @date 29/12/2024
 * @author CHIPY Thibault
 */
class Validator{
    /**
     * @brief Regles de validation 
     *
     * @var array
     */
   private array $regles = []; 

   /**
    * @brief Messages d'erreurs
    *@var array
    */
    private array $messagesErreurs = [];

    /**
     * @brief Constructeur de la classe Validator 
     * @param array $regles : règles de validation
     *
     * @param array $regles
     */
    public function __construct(array $regles){
        $this->regles = $regles;
    }

    /**
     * @brief Valider les données d'un formulaire avec les règles de validation
     * @param array $donnees : données du formulaire
     * @return bool : true si les données sont valides, false sinon
     */
    public function valider(array $donnees): bool{
        $valide=true;
        $this->messagesErreurs = [];

        foreach($this->regles as $champ => $regleChamp){
            $valeur = $donnees[$champ]??null;
            if(!$this->validerChamp($champ, $valeur, $regleChamp)){
                $valide = false;
            }
        }
        return $valide;
    } 

    /**
     * @brief Valider un champ d'un formulaire
     * @param string $champ : nom du champ
     * @param mixed $valeur : valeur du champ
     * @param array $regleChamp : règles de validation du champ
     * @return bool : true si le champ est valide, false sinon
     */

    public function validerChamp(string $champ, $valeur, array $regles): bool{
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

    /**
     * @brief Obtenir les messages d'erreurs
     * 
     * @return array
     */
    public function getMessagesErreurs(): array
    {
        return $this->messagesErreurs;
    }
}
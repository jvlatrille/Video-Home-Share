<?php
//ajout de la classe IntlExtension et creation de l’alias IntlExtension
use Twig\Extra\Intl\IntlExtension;

//initialisation twig : chargement du dossier contenant les templates
$loader = new Twig\Loader\FilesystemLoader('templates');

//Paramétrage de l'environnement twig
$twig = new Twig\Environment($loader, [
    /*passe en mode debug à enlever en environnement de prod : permet d'utiliser dans un templates {{dump
    (variable)}} pour afficher le contenu d'une variable. Nécessite l'utilisation de l'extension debug*/
    'debug' => true,
    // 'cache' => '../cache',
    // Il est possible de définir d'autre variable d'environnement
    //...
]);

//Définition de la timezone pour que les filtres date tiennent compte du fuseau horaire français.
$twig->getExtension(\Twig\Extension\CoreExtension::class)->setTimezone('Europe/Paris');

//Ajouter l'extension debug
$twig->addExtension(new \Twig\Extension\DebugExtension());

//Ajout de l'extension d'internationalisation qui permet d'utiliser les filtres de date dans twig
$twig->addExtension(new IntlExtension());

use Symfony\Component\Yaml\Yaml;

// Chemin vers le fichier YAML
$configPath = __DIR__ . '/constantes.yaml';

if (file_exists($configPath)) {
    // Charger les constantes depuis le fichier YAML
    $constants = Yaml::parseFile($configPath);

    if (!empty($constants)) {
        foreach ($constants as $section => $values) {
            if (is_array($values)) {
                foreach ($values as $key => $value) {
                    // Ajouter les constantes comme variables globales accessibles dans Twig
                    $twig->addGlobal(strtolower($section) . '_' . strtolower($key), $value);
                }
            } else {
                // Ajouter cette constante comme variable globale
                $twig->addGlobal(strtolower($section), $values);
            }
        }
    }
} else {
    die('Le fichier config/constantes.yaml est introuvable.');
}

require_once 'modeles/utilisateur.class.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['utilisateur']) && ! empty($_SESSION['utilisateur'])) {
    $utilisateur = unserialize($_SESSION['utilisateur']);
    $twig->addGlobal('utilisateurConnecte', $utilisateur);
} else {
    $twig->addGlobal('utilisateurConnecte', null);
}

<?php
// Chargement des constantes depuis config/constantes.yaml
use Symfony\Component\Yaml\Yaml;

// Chemin vers le fichier de configuration YAML
$configPath = __DIR__ . '/constantes.yaml';

if (file_exists($configPath)) {
    // Parse le fichier YAML pour obtenir les configurations
    $config = Yaml::parseFile($configPath);

    // Définir les constantes globales en PHP à partir des configurations
    foreach ($config as $section => $values) {
        if (is_array($values)) { // Correction : pas besoin du "value:" pour l'argument
            foreach ($values as $key => $value) {
                // Concaténer la section et la clé pour former le nom de la constante
                $constantName = strtoupper($section) . '_' . strtoupper($key);
                if (!defined($constantName)) {
                    define($constantName, $value);
                }
            }
        } else {
            // Si ce n'est pas un tableau
            $constantName = strtoupper($section);
            if (!defined($constantName)) {
                define($constantName, $values);
            }
        }
    }
} else {
    die('Le fichier config/constantes.yaml est introuvable.');
}

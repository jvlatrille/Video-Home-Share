<?php
/**
 * @file backupBD.php 
 * @brief Script permettant de sauvegarder la BD réguliérement grâce au planificateur de tâches windows
 * @version 1
 * @date 24/01/2025
 * @author VINET LATRILLE Jules
 */

require_once __DIR__ . '/../vendor/autoload.php';
use Symfony\Component\Yaml\Yaml;

// On charge les constantes
$configPath = __DIR__ . '/../config/constantes.yaml';
if (!file_exists($configPath)) {
    die("Erreur : Le fichier constantes.yaml est introuvable.\n");
}
$config = Yaml::parseFile($configPath);
$dbConfig = $config['DB_'];
$prefixeTable = $config['PREFIXE_TABLE'];

// Connexion à la BD
$host = $dbConfig['HOST'];
$dbName = $dbConfig['NAME'];
$user = $dbConfig['USER'];
$password = $dbConfig['PASS'];
$conn = new mysqli($host, $user, $password, $dbName);
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Configurer l'encodage UTF-8 (pour les accents)
$conn->set_charset("utf8mb4");

// Nom du fichier de sauvegarde avec année-mois-etc...
$date = date('Y-m-d-H-i-s');
$backupDir = __DIR__ . '/../backupBD/';
$backupFile = $backupDir . $date . '.sql';

// Joli mise en page :)
$backupSql = "-- Sauvegarde complète des tables préfixées par $prefixeTable dans $dbName\n";
$backupSql .= "-- Générée le $date\n\n";

// Récupérer toutes les tables de la base
$tables = $conn->query("SHOW TABLES");
if (!$tables) {
    die("Erreur lors de la récupération des tables : " . $conn->error);
}

while ($row = $tables->fetch_row()) {
    $table = $row[0];

    // Filtrer les tables selon le préfixe
    if (strpos($table, $prefixeTable) === 0) {
        // Exporter la structure de la table
        $createTable = $conn->query("SHOW CREATE TABLE $table")->fetch_row()[1];
        $backupSql .= "-- Structure de la table $table\n";
        $backupSql .= "$createTable;\n\n";

        // Exporter les données de la table
        $backupSql .= "-- Données de la table $table\n";
        $rows = $conn->query("SELECT * FROM $table");
        while ($data = $rows->fetch_assoc()) {
            $escapedValues = [];
            foreach ($data as $value) {
                if (is_null($value)) {
                    $escapedValues[] = 'NULL';
                } else {
                    // Remplace les apostrophes simples par deux apostrophes pour SQL
                    $escapedValue = str_replace("'", "''", $value);
                    $escapedValues[] = "'" . $escapedValue . "'";
                }
            }
            $backupSql .= "INSERT INTO $table VALUES (" . implode(',', $escapedValues) . ");\n";
        }
        $backupSql .= "\n";
    }
}

// Écrire le fichier de sauvegarde
file_put_contents($backupFile, $backupSql);

// Insérer la date de sauvegarde dans la table `vhs_derniereSave`
$conn->query("INSERT INTO vhs_derniereSave (date_save) VALUES ('$date')");

// Terminer le script
echo "Sauvegarde complète des tables avec préfixe \"$prefixeTable\" terminée dans : $backupFile\n";

// Fermer la connexion
$conn->close();
?>
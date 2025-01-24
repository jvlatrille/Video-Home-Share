<?php
// Inclure l'autoloader de Composer (si nécessaire)
require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

// Chemin vers le fichier constantes.yaml
$configPath = __DIR__ . '/../config/constantes.yaml';

// Charger les constantes YAML
if (!file_exists($configPath)) {
    die("Erreur : Le fichier constantes.yaml est introuvable.\n");
}

$config = Yaml::parseFile($configPath);
$dbConfig = $config['DB_'];
$prefixeTable = $config['PREFIXE_TABLE'];

// Connexion à la base de données
$host = $dbConfig['HOST'];
$dbName = $dbConfig['NAME'];
$user = $dbConfig['USER'];
$password = $dbConfig['PASS'];

$conn = new mysqli($host, $user, $password, $dbName);
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Générer le nom du fichier de sauvegarde
$date = date('Y-m-d-H-i-s');
$backupDir = __DIR__ . '/../backupBD/';
$backupFile = $backupDir . $date . '.sql';

// Vérifier si le dossier backupBD existe, sinon le créer
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0777, true);
}

// Préparer la sauvegarde
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
                    $escapedValues[] = "'" . $conn->real_escape_string($value) . "'";
                }
            }
            $backupSql .= "INSERT INTO $table VALUES (" . implode(',', $escapedValues) . ");\n";
        }
        $backupSql .= "\n";
    }
}

// Écrire le fichier de sauvegarde
file_put_contents($backupFile, $backupSql);

// Terminer le script
echo "Sauvegarde complète des tables avec préfixe \"$prefixeTable\" terminée dans : $backupFile\n";

// Fermer la connexion
$conn->close();
?>
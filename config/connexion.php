<?php
// A REMPLACER PAR DES MODELES / CONTROLEURS
try {
    $pdo = new PDO('mysql:host='. DB_HOST . ';dbname='. DB_NAME, DB_USER, DB_PASS);
} catch (PDOException $e) {
    echo 'Connexion à la base de données échouée : ' . $e->getMessage();
    die();
}
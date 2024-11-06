<?php
class Bd{
    private static ?Bd $instance = null;

    private ?PDO $pdo;

    private function __construct() {
        try {
            $this->pdo = new PDO('mysql:host='. DB_HOST . ';dbname='. DB_NAME, DB_USER, DB_PASS);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }
    catch (PDOException $e){
        $message = 'Problème de connexion à la base de données'. $e->getMessage();
        echo $message;
        die();
    }}

    public static function getInstance(): Bd {
        if(self::$instance === null){
            self::$instance = new Bd();
        }
        return self::$instance;
    }

    public function getConnexion(): PDO {
        return $this->pdo;

}

    private function __clone() {
    }

    public function __wakeup() {
        throw new Exception("Un singleton ne dois pas être deserialisé.");
    }
}
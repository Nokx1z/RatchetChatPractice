<?php 
namespace ratchetchatpractice\core;
use PDO;
use PDOException;

class Database {

    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        
        try {
            $this->pdo = new PDO(
                'mysql:host=localhost;dbname=ratchetchatpractice;charset=utf8',
                'admin',
                '',
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);  
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }

    }

        public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Devuelve la conexión PDO.
     * @return PDO
     */
    public function getConnection() {
        return $this->pdo;
    }
}


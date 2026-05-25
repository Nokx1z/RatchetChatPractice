<?php
namespace Deadt\RatchetChatPractice\Core;

use PDO;
use PDOException;

class Connection {
    private static ?PDO $instance = null; // Guardamos la instancia única

    // Constructor privado para evitar crear objetos con "new Connection()"
    private function __construct() {}

    // Evitar que se pueda clonar el objeto
    private function __clone() {}

    // Evitar que se pueda deserializar el objeto
    public function __wakeup() {
        throw new \Exception("No se puede deserializar un Singleton.");
    }

    // Método público para obtener la única instancia
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    "mysql:host=localhost;dbname=ratchet_chat;charset=utf8mb4",
                    "root", // Usuario de MySQL
                    ""      // Contraseña (ajusta si tienes)
                );
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
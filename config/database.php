<?php
// Configuración de la base de datos (ajusta según tu setup)
define('DB_HOST', 'localhost');
define('DB_NAME', 'chat_app');
define('DB_USER', 'root');
define('DB_PASS', ''); // Vacío para XAMPP por defecto

// Función para obtener conexión PDO (singleton-like para reutilizar)
function getDatabaseConnection() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Conexión a MySQL exitosa.\n";
        } catch (PDOException $e) {
            die("Error de conexión a MySQL: " . $e->getMessage() . "\n");
        }
    }
    return $pdo;
}
?>
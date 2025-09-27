-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS chat_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Usar la DB
USE chat_app;

-- Crear tabla de mensajes
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    type ENUM('message', 'system', 'login', 'disconnect') DEFAULT 'message',
    INDEX idx_timestamp (timestamp) -- Para consultas rápidas por tiempo
);

-- Insertar un mensaje de prueba (opcional, para verificar)
INSERT INTO messages (username, message, type) VALUES ('Sistema', 'Base de datos inicializada.', 'system');
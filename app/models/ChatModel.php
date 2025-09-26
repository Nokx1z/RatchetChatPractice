<?php
namespace Deadt\RatchetChatPractice\Models;

use Deadt\RatchetChatPractice\Core\Connection;
use PDO;

class ChatModel {

    // Obtener todos los mensajes
    public function getAllMessages(): array {
        $db = Connection::getInstance();
        $stmt = $db->query("
            SELECT m.id, u.username, m.message, m.created_at 
            FROM messages m
            JOIN users u ON m.user_id = u.id
            ORDER BY m.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Guardar un mensaje
    public function saveMessage(int $userId, string $message): bool {
        $db = Connection::getInstance();
        $stmt = $db->prepare("INSERT INTO messages (user_id, message) VALUES (:user_id, :message)");
        return $stmt->execute([
            ':user_id' => $userId,
            ':message' => $message
        ]);
    }

    // Validar usuario y devolver su ID
    public function getUserIdByUsername(string $username): ?int {
        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT id FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int) $result['id'] : null;
    }

    // Registrar nuevo usuario con regex de validación
    public function registerUser(string $username, string $password): bool {
        // Validaciones con expresiones regulares
        if (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
            throw new \Exception("El nombre de usuario no es válido.");
        }
        if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/", $password)) {
            throw new \Exception("La contraseña debe tener al menos 6 caracteres, incluir letras y números.");
        }

        $db = Connection::getInstance();
        $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        return $stmt->execute([
            ':username' => $username,
            ':password' => password_hash($password, PASSWORD_DEFAULT) // nunca guardes contraseñas planas
        ]);
    }
}
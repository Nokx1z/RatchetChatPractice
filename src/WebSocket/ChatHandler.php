<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require_once dirname(__DIR__, 2) . '/config/database.php';

class ChatHandler implements MessageComponentInterface {
    protected $clients;
    protected $users; 
    protected $pdo; 

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->users = [];
        $this->pdo = getDatabaseConnection();
        echo "Handler de chat inicializado. Esperando conexiones...\n";
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        $this->users[$conn->resourceId] = null; 
        echo "Nueva conexión: {$conn->resourceId}\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);
        if (!$data) return;

        $type = $data['type'] ?? '';
        $username = $data['username'] ?? null;
        $message = $data['message'] ?? '';

        $currentUser = $this->users[$from->resourceId] ?? 'Anónimo';

        if ($type === 'login' && $username) {
            $this->users[$from->resourceId] = $username;
            
            $stmt = $this->pdo->prepare("INSERT INTO messages (username, message, type, timestamp) VALUES (?, ?, 'login', NOW())");
            $stmt->execute([$username, "{$username} se ha conectado."]);
            
            $broadcastData = [
                'type' => 'login',
                'username' => $username,
                'message' => "{$username} se ha conectado.",
                'users' => array_values(array_filter($this->users)),
                'timestamp' => date('H:i:s'),
                'db_id' => $this->pdo->lastInsertId()
            ];
            $this->broadcast($broadcastData, $from);
            
            $this->sendHistory($from);
            
            return;
        }

        if ($type === 'message' && $currentUser !== null) {
            // Insertar en DB
            $stmt = $this->pdo->prepare("INSERT INTO messages (username, message, type, timestamp) VALUES (?, ?, 'message', NOW())");
            $stmt->execute([$currentUser, $message]);
            
            $timestamp = date('H:i:s');
            $broadcastData = [
                'type' => 'message',
                'username' => $currentUser,
                'message' => $message,
                'timestamp' => $timestamp,
                'db_id' => $this->pdo->lastInsertId()
            ];
            $this->broadcast($broadcastData, $from);

            // Eco para el emisor
            $from->send(json_encode([
                'type' => 'echo',
                'username' => $currentUser,
                'message' => $message,
                'timestamp' => $timestamp,
                'db_id' => $broadcastData['db_id']
            ]));
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $resourceId = $conn->resourceId;
        $username = $this->users[$resourceId] ?? 'Anónimo';
        unset($this->users[$resourceId]);

        $stmt = $this->pdo->prepare("INSERT INTO messages (username, message, type, timestamp) VALUES (?, ?, 'disconnect', NOW())");
        $stmt->execute([$username, "{$username} se ha desconectado."]);

        $timestamp = date('H:i:s');
        $broadcastData = [
            'type' => 'disconnect',
            'username' => $username,
            'message' => "{$username} se ha desconectado.",
            'users' => array_values(array_filter($this->users)),
            'timestamp' => $timestamp,
            'db_id' => $this->pdo->lastInsertId()
        ];
        $this->broadcast($broadcastData, $conn);

        $this->clients->detach($conn);
        echo "Conexión {$resourceId} cerrada.\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }

    private function broadcast($data, ConnectionInterface $from = null) {
        foreach ($this->clients as $client) {
            if ($from === null || $from !== $client) {
                $client->send(json_encode($data));
            }
        }
    }

    private function sendHistory(ConnectionInterface $conn) {
        $stmt = $this->pdo->query("SELECT username, message, type, DATE_FORMAT(timestamp, '%H:%i:%s') as timestamp FROM messages ORDER BY timestamp DESC LIMIT 50");
        $history = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $history = array_reverse($history);
        
        $conn->send(json_encode([
            'type' => 'history',
            'messages' => $history
        ]));
    }
}
?>

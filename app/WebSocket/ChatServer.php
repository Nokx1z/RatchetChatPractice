<?php
namespace App\WebSocket;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class ChatServer implements MessageComponentInterface
{
    private ConnectionPool $pool;

    /** @var array<string,array{id:string,name:string}> */
    private array $byConnId = [];

    public function __construct()
    {
        $this->pool = new ConnectionPool();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $queryString = '';
        if (isset($conn->httpRequest)) {
            $uri = $conn->httpRequest->getUri();
            if (is_object($uri) && method_exists($uri, 'getQuery')) {
                $queryString = (string) $uri->getQuery();
            }
        }
        parse_str($queryString, $query);
        $sid = (string)($query['sid'] ?? '');
        $uid = isset($query['uid']) ? (string)$query['uid'] : '';
        $name = isset($query['name']) ? (string)$query['name'] : '';

        // Identidad del usuario (demo): priorizar uid+name si vienen de la app HTTP
        if ($uid !== '' && $name !== '') {
            $user = ['id' => $uid, 'name' => $name];
        } elseif ($sid !== '') {
            $user = ['id' => substr(hash('sha256', $sid), 0, 8), 'name' => 'User-' . substr($sid, -4)];
        } else {
            $user = ['id' => substr(hash('sha256', (string)$conn->resourceId), 0, 8), 'name' => 'Usuario'];
        }

        $this->byConnId[$conn->resourceId] = $user;
        $this->pool->attach($user['id'], $conn);
        $conn->send(json_encode(['type'=>'history','messages'=>[]]));
        $this->broadcast(MessageProtocol::makeSystem($user['name'] . ' se ha conectado.'), $conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $user = $this->byConnId[$from->resourceId] ?? null;
        if (!$user) { $from->close(); return; }
        $data = json_decode($msg, true);
        if (!is_array($data) || ($data['type'] ?? '') !== 'message') {
            $from->send(json_encode(MessageProtocol::makeSystem('Formato inválido.')));
            return;
        }
        $body = trim((string)($data['body'] ?? ''));
        if ($body === '') return;
        $to = isset($data['to']) && $data['to'] !== null ? (string)$data['to'] : null;

        $payload = MessageProtocol::makeMessage($user, $to, $body);
        if ($to) {
            $dest = $this->pool->getByUser($to);
            if ($dest) { $dest->send(json_encode($payload)); }
            $from->send(json_encode($payload));
        } else {
            $this->broadcast($payload);
        }
        // Notificación para otros
        $this->broadcast(MessageProtocol::makeNotification($user, $body), $from);
    }

    public function onClose(ConnectionInterface $conn)
    {
        $user = $this->byConnId[$conn->resourceId] ?? null;
        $this->pool->detachByConn($conn);
        unset($this->byConnId[$conn->resourceId]);
        if ($user) {
            $this->broadcast(MessageProtocol::makeSystem(($user['name'] ?? 'Alguien') . ' se desconectó.'));
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->send(json_encode(MessageProtocol::makeSystem('Error: ' . $e->getMessage())));
        $conn->close();
    }

    private function broadcast(array $payload, ?ConnectionInterface $except = null): void
    {
        $json = json_encode($payload);
        foreach ($this->pool->all() as $uid => $c) {
            if ($except && $c === $except) continue;
            $c->send($json);
        }
    }
}

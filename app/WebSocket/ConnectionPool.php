<?php
namespace App\WebSocket;

use Ratchet\ConnectionInterface;

class ConnectionPool
{
    /** @var array<string, ConnectionInterface> */
    private array $byUser = [];

    public function attach(string $userId, ConnectionInterface $conn): void
    {
        $this->byUser[$userId] = $conn;
    }

    public function detachByConn(ConnectionInterface $conn): void
    {
        foreach ($this->byUser as $uid => $c) {
            if ($c === $conn) { unset($this->byUser[$uid]); break; }
        }
    }

    public function getByUser(string $userId): ?ConnectionInterface
    {
        return $this->byUser[$userId] ?? null;
    }

    /** @return array<string, ConnectionInterface> */
    public function all(): array
    {
        return $this->byUser;
    }
}

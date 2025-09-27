<?php
namespace App\WebSocket;

class MessageProtocol
{
    public static function makeMessage(array $from, ?string $to, string $body): array
    {
        return [
            'type' => 'message',
            'id' => substr(hash('sha256', $from['id'] . microtime(true)), 0, 8),
            'from' => ['id' => $from['id'], 'name' => $from['name']],
            'to' => $to,
            'body' => $body,
            'created_at' => date('c'),
        ];
    }

    public static function makeNotification(array $from, string $preview): array
    {
        return [
            'type' => 'notification',
            'from' => ['id' => $from['id'], 'name' => $from['name']],
            'preview' => mb_substr($preview, 0, 80),
            'created_at' => date('c'),
        ];
    }

    public static function makeSystem(string $message): array
    {
        return ['type' => 'system', 'message' => $message];
    }
}

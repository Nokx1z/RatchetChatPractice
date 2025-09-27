#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';

use App\WebSocket\ChatServer;
use Dotenv\Dotenv;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->safeLoad();
}

$bindHost = $_ENV['WS_BIND'] ?? '0.0.0.0';
$port = (int)($_ENV['WS_PORT'] ?? 8080);

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ChatServer()
        )
    ),
    $port,
    $bindHost
);

echo "WebSocket escuchando en ws://{$bindHost}:{$port}\n";
$server->run();

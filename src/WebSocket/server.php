<?php
require dirname(__DIR__) . '../../vendor/autoload.php';
require_once __DIR__ . './ChatHandler.php';

// Inicia el servidor usando la clase modular
$server = \Ratchet\Server\IoServer::factory(
    new \Ratchet\Http\HttpServer(
        new \Ratchet\WebSocket\WsServer(
            new ChatHandler()
        )
    ),
    8080
);

$server->run();
?>

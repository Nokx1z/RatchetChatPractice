<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/ChatHandler.php';

$server = \Ratchet\Server\IoServer::factory(
    new \Ratchet\Http\HttpServer(
        new \Ratchet\WebSocket\WsServer(
            new ChatHandler()
        )
    ),
    8081
);
$server->run();
?>

<?php
namespace App\Controllers;

use App\Helpers\View;

class ChatController
{
    public function index(): string
    {
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $host = $_ENV['WS_HOST'] ?? '127.0.0.1';
        $port = (int)($_ENV['WS_PORT'] ?? 8080);
        $token = session_id();
        $uid = (string)($_SESSION['user']['id'] ?? '');
        $name = (string)($_SESSION['user']['name'] ?? '');
        $wsUrl = sprintf(
            'ws://%s:%d?sid=%s&uid=%s&name=%s',
            $host,
            $port,
            urlencode($token),
            urlencode($uid),
            urlencode($name)
        );

        return View::render('chat/index', [
            'title' => 'Chat',
            'wsUrl' => $wsUrl,
            'authUser' => $_SESSION['user'],
        ]);
    }
}

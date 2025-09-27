<?php
namespace App\Controllers;

use App\Helpers\View;

class AuthController
{
    public function home(): string
    {
        if (!empty($_SESSION['user'])) {
            header('Location: /chat');
            exit;
        }
        header('Location: /login');
        exit;
    }

    public function showLogin(): string
    {
        if (!empty($_SESSION['user'])) {
            header('Location: /chat');
            exit;
        }
        return View::render('auth/login', [
            'title' => 'Login',
            'error' => $_SESSION['flash_error'] ?? null,
        ]);
    }

    public function login(): string
    {
        $name = trim($_POST['name'] ?? '');
        if ($name === '') {
            $_SESSION['flash_error'] = 'Nombre requerido';
            header('Location: /login');
            exit;
        }
        // Autenticación simplificada (sin contraseña) para demo de 3 usuarios
        $_SESSION['user'] = [
            'id' => substr(hash('sha256', $name), 0, 8),
            'name' => $name,
        ];
        unset($_SESSION['flash_error']);
        header('Location: /chat');
        exit;
    }

    public function logout(): string
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();
        header('Location: /login');
        exit;
    }
}

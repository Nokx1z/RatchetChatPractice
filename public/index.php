<?php
declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\ChatController;
use Dotenv\Dotenv;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

require_once __DIR__ . '/../vendor/autoload.php';

// Bootstrap: Dotenv + sesión
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->safeLoad();
}

session_start();

// Ruteo con FastRoute
$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
    // Rutas básicas
    $r->addRoute('GET', '/', [AuthController::class, 'home']);
    $r->addRoute('GET', '/login', [AuthController::class, 'showLogin']);
    $r->addRoute('POST', '/login', [AuthController::class, 'login']);
    $r->addRoute('POST', '/logout', [AuthController::class, 'logout']);
    $r->addRoute('GET', '/chat', [ChatController::class, 'index']);
});

// Obtener método y URI
$httpMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo '404 Not Found';
        break;
    case Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo '405 Method Not Allowed';
        break;
    case Dispatcher::FOUND:
        [$class, $method] = $routeInfo[1];
        $vars = $routeInfo[2];
        $controller = new $class();
        echo call_user_func_array([$controller, $method], [$vars]);
        break;
}
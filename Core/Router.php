<?php
namespace Deadt\RatchetChatPractice\Core;

class Router {
    public function hello() {
        return "Hola desde Router!";
    }
}
/*
class Router {
    private $routes = [];

    public function get($path, $controllerAction) {
        $this->routes['GET'][$path] = $controllerAction;
    }

    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = strtok($_SERVER['REQUEST_URI'], '?');

        if (isset($this->routes[$method][$uri])) {
            [$controller, $action] = explode('@', $this->routes[$method][$uri]);
            $controllerClass = "Deadt\\RatchetChatPractice\\Controllers\\$controller";
            $c = new $controllerClass();
            call_user_func([$c, $action]);
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
    }
}
*/
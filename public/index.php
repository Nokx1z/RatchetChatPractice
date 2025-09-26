<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Deadt\RatchetChatPractice\Core\Router;

$router = new Router();
echo $router->hello();
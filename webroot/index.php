<?php

use JsPhpize\Nodes\Main;
use SimplePHPFramework\controllers\MainController;
use SimplePHPFramework\kernel\Router;

require __DIR__ . "/../vendor/autoload.php";

// Controllers Instans
$mainController = new MainController;
// Router
$router = new Router();

// You must declare the routers in this place
$router->get('/', [$mainController, "index"]);
$router->get('/login', [$mainController, "login"]);
$router->post('/login', [$mainController, "loginRequest"]);


$router->start();

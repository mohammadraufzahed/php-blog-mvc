<?php

use JsPhpize\Nodes\Main;
use SimplePHPFramework\controllers\AuthController;
use SimplePHPFramework\controllers\MainController;
use SimplePHPFramework\kernel\Router;

require __DIR__ . "/../vendor/autoload.php";

// Controllers Instans
$mainController = new MainController;
$authController = new AuthController;
// Router
$router = new Router();

// You must declare the routers in this place
$router->get('/', [$mainController, "index"]);
$router->get('/login', [$authController, "login"]);
$router->post('/login', [$authController, "loginRequest"]);
$router->get('/signup', [$authController, "signup"]);
$router->post('/signup', [$authController, "signupRequest"]);

$router->start();

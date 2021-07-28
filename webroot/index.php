<?php

use SimplePHPFramework\controllers\AuthController;
use SimplePHPFramework\controllers\MainController;
use SimplePHPFramework\controllers\DashboardController;
use SimplePHPFramework\kernel\Router;

require __DIR__ . "/../vendor/autoload.php";

// Controllers Instans
$mainController = new MainController();
$authController = new AuthController();
$dashboardController = new DashboardController();
// Router
$router = new Router();

// You must declare the routers in this place

// Home Page
$router->get('/', [$mainController, "index"]);
// Auth Pages
$router->get('/login', [$authController, "login"]);
$router->post('/login', [$authController, "loginRequest"]);
$router->get('/signup', [$authController, "signup"]);
$router->post('/signup', [$authController, "signupRequest"]);
$router->get('/logout', [$authController, 'logout']);
// Admin Pages
$router->get('/dashboard', [$dashboardController, "index"]);
$router->get('/dashboard/posts', [$dashboardController, "posts"]);
$router->get('/dashboard/posts/new', [$dashboardController, "newPost"]);
$router->post('/dashboard/posts/new', [$dashboardController, "newPostAction"]);


$router->start();

<?php

use SimplePHPFramework\controllers\AuthController;
use SimplePHPFramework\controllers\MainController;
use SimplePHPFramework\controllers\DashboardController;
use SimplePHPFramework\controllers\PostController;
use SimplePHPFramework\controllers\UsersController;
use SimplePHPFramework\kernel\Router;

require __DIR__ . "/../vendor/autoload.php";

// Controllers Instans
$authController = new AuthController();
$dashboardController = new DashboardController();
$postController = new PostController();
$usersController = new UsersController();

// Router
$router = new Router();

// You must declare the routers in this place

// Auth Routes 
$router->route("POST", '/login', [$authController, "loginRequest"]);
$router->route("POST", '/signup', [$authController, "signupRequest"]);
$router->route("GET", '/logout', [$authController, 'logout']);
// Admin Pages
$router->route("POST", '/dashboard/posts/new', [$postController, "newPostAction"]);
$router->route("POST", '/dashboard/posts/edit', [$postController, "updatePost"]);
$router->route("GET", '/dashboard/posts/delete', [$postController, "deletePost"]);
$router->route("GET", '/dashboard/users/delete', [$usersController, "delete"]);
$router->route("POST", '/dashboard/users/edit', [$usersController, "update"]);
$router->route("POST", "/dashboard/users/new", [$usersController, "newAction"]);
$router->route("POST", '/dashboard/settings/site', [$dashboardController, "settingAction"]);

$router->start();

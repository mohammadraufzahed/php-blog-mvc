<?php

use SimplePHPFramework\controllers\AuthController;
use SimplePHPFramework\controllers\MainController;
use SimplePHPFramework\controllers\DashboardController;
use SimplePHPFramework\controllers\PostController;
use SimplePHPFramework\controllers\UsersController;
use SimplePHPFramework\kernel\Router;

require __DIR__ . "/../vendor/autoload.php";

// Controllers Instans
$mainController = new MainController();
$authController = new AuthController();
$dashboardController = new DashboardController();
$postController = new PostController();
$usersController = new UsersController();

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
$router->get('/dashboard/posts', [$postController, "posts"]);
$router->get('/dashboard/posts/new', [$postController, "newPost"]);
$router->post('/dashboard/posts/new', [$postController, "newPostAction"]);
$router->get('/dashboard/posts/edit', [$postController, "editPost"]);
$router->post('/dashboard/posts/edit', [$postController, "updatePost"]);
$router->get('/dashboard/posts/delete', [$postController, "deletePost"]);
$router->get('/dashboard/users', [$usersController, "index"]);
$router->get('/dashboard/users/delete', [$usersController, "delete"]);
$router->get('/dashboard/users/edit', [$usersController, "edit"]);
$router->post('/dashboard/users/edit', [$usersController, "update"]);
$router->get("/dashboard/users/new", [$usersController, "new"]);
$router->post("/dashboard/users/new", [$usersController, "newAction"]);
$router->get("/dashboard/settings/account", [$usersController, "userSetting"]);

$router->start();

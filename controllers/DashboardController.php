<?php

namespace SimplePHPFramework\controllers;

require __DIR__ . "/../vendor/autoload.php";

use SimplePHPFramework\kernel\View;
use SimplePHPFramework\models\DashboardModel;
use SimplePHPFramework\models\PostsModel;

class DashboardController
{
    private View $viewEngine;
    private DashboardModel $dashboardModel;
    private PostsModel $postsModel;

    public function __construct()
    {
        // Initialize the viewEngine
        $this->viewEngine = new View;
        $this->dashboardModel = new DashboardModel();
        $this->postsModel = new PostsModel();
    }

    public function index()
    {
        $this->permission();
        session_start();
        $username = $_SESSION["username"];
        $signupDate = $this->dashboardModel->signupDate($username)->created_at;
        $countPost = $this->dashboardModel->countPosts()->countPost;
        $countUser = $this->dashboardModel->countUser()->countUser;
        $allPosts = $this->postsModel->fetchAllPosts();
        $this->viewEngine->render("dashboard/home/index.pug", [
            "title" => "PHP MVC Blog | Dashboard",
            "username" => $username,
            "signupDate" => $signupDate,
            "countPost" => $countPost,
            "countUser" => $countUser,
            "allPosts" => $allPosts

        ]);
    }

    public function posts()
    {
        $this->permission();
        $allPosts = $this->postsModel->fetchAllPosts();
        $this->viewEngine->render("dashboard/posts/form/index.pug", [
            "allPosts" => $allPosts
        ]);
    }

    private function permission()
    {
        if (!AuthController::isLoggedIn()) {
            header("location: /");
            exit;
        }
    }
}

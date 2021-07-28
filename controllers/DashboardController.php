<?php

namespace SimplePHPFramework\controllers;

require __DIR__ . "/../vendor/autoload.php";

use Rakit\Validation\Validator;

use SimplePHPFramework\kernel\View;
use SimplePHPFramework\models\DashboardModel;
use SimplePHPFramework\models\PostsModel;
use SimplePHPFramework\models\AuthModel;

class DashboardController
{
    private View $viewEngine;
    private DashboardModel $dashboardModel;
    private PostsModel $postsModel;
    private AuthModel $authModel;

    public function __construct()
    {
        // Initialize the viewEngine
        $this->viewEngine = new View;
        $this->dashboardModel = new DashboardModel();
        $this->postsModel = new PostsModel();
        $this->authModel = new AuthModel();
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

    public function newPost()
    {
        $this->permission();
        return $this->viewEngine->render('dashboard/posts/new/index.pug');
    }

    public function newPostAction()
    {
        $this->permission();
        // Validate the Data
        $validator = new Validator();
        $validation = $validator->make($_POST, [
            'title' => 'required',
            'body' => 'required|min:20',
            'status' => 'required'
        ]);
        // If validation was failed, return the errors
        if ($validation->fails()) {
            dd($validation->errors());
        }
        // If validation was success, save the title and body and status from the post request
        $title = $_POST['title'];
        $body = $_POST['body'];
        $status = $_POST['status'];
        // Start the session and save the username
        session_start();
        $username = $_SESSION["username"];
        // Get the user id from the Auth model
        $user_id = $this->authModel->getUserId($username);
        // Save the post in the database and if the post was saved, redirect to the posts page
        if ($this->postsModel->savePost($user_id, $title, $body, $status)) {
            $this->viewEngine->redirect("/dashboard/posts");
        }
    }

    private function permission()
    {
        if (!AuthController::isLoggedIn()) {
            $this->viewEngine->redirect("/login");
        }
    }
}

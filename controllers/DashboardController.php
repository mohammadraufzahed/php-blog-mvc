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
        $signupDate = $this->dashboardModel->signupDate($username);
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

    /**
     * Render the settings page
     */
    public function settingPage(): void
    {
        // Check the permission
        $this->permission();
        // Get the data
        $data = $this->dashboardModel->getSettings();
        // Render the view
        $this->viewEngine->render("dashboard/settings/home/index.pug", [
            "blog" => $data
        ]);
    }

    /**
     * Handle the post request to settings
     */
    public function settingAction(): void
    {
        // Check the permission
        $this->permissionAdmin();
        // Verify the data
        $validator = new Validator();
        $validation = $validator->make($_POST, [
            "blogTitle" => "required|min:3",
            "blogDescription" => "required|min:3",
            "author" => "required",
            "authorDescription" => "required"
        ]);
        $validation->validate();
        if($validation->fails())
        {
            dd($validation->errors());
        } else {
            $title = $_POST["blogTitle"];
            $description = $_POST["blogDescription"];
            $author = $_POST["author"];
            $authorInfo = $_POST["authorDescription"];
            if($this->dashboardModel->setSettings($title, $description, $author, $authorInfo))
            {
                $this->viewEngine->redirect("/dashboard");
                exit();
            }
        }

    }

    private function permission()
    {
        if (!AuthController::isLoggedIn()) {
            $this->viewEngine->redirect("/login");
        }
    }
    private function permissionAdmin()
{
    if (!AuthController::isLoggedIn() && !AuthController::isAdmin()) {
        $this->viewEngine->redirect("/login");
    }
}
}

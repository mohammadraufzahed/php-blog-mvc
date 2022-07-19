<?php

namespace SimplePHPFramework\controllers;

require __DIR__ . "/../vendor/autoload.php";

use Exception;
use Rakit\Validation\Validator;


use SimplePHPFramework\models\DashboardModel;
use SimplePHPFramework\models\PostsModel;
use SimplePHPFramework\models\AuthModel;

class DashboardController
{

    private DashboardModel $dashboardModel;

    public function __construct()
    {


        $this->dashboardModel = new DashboardModel();
        $this->postsModel = new PostsModel();
    }

    /**
     * Handle the post request to settings
     */
    public function settingAction(): void
    {
        // Check the permission
        try {
            $this->permissionAdmin();
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(["status" => "unauthorized"]);
            exit;
        }
        // Verify the data
        $validator = new Validator();
        $validation = $validator->make($_POST, [
            "blogTitle" => "required|min:3",
            "blogDescription" => "required|min:3",
            "author" => "required",
            "authorDescription" => "required"
        ]);
        $validation->validate();
        if ($validation->fails()) {
            echo json_encode(['status' => 'faild']);
        } else {
            $title = $_POST["blogTitle"];
            $description = $_POST["blogDescription"];
            $author = $_POST["author"];
            $authorInfo = $_POST["authorDescription"];
            if ($this->dashboardModel->setSettings($title, $description, $author, $authorInfo)) {
                echo json_encode(["status" => "ok"]);
            }
        }
    }

    private function permissionAdmin()
    {
        if (!AuthController::isLoggedIn() && !AuthController::isAdmin()) {
            throw new Exception();
        }
    }
}

<?php

namespace SimplePHPFramework\controllers;

use Exception;
use Rakit\Validation\Validator;
use SimplePHPFramework\models\PostsModel;
use SimplePHPFramework\models\AuthModel;

require __DIR__ . "/../vendor/autoload.php";


class PostController
{
    private PostsModel $postsModel;
    private AuthModel $authModel;

    public function __construct()
    {

        // Initialize the postsModel
        $this->postsModel = new PostsModel();
        // Initialize the authModel
        $this->authModel = new AuthModel();
    }

    public function newPostAction()
    {
        try {
            $this->permission();
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(["status" => "faild"]);
            exit;
        }
        // Validate the Data
        $validator = new Validator();
        $validation = $validator->make($_POST, [
            'title' => 'required',
            'body' => 'required|min:20',
            'status' => 'required'
        ]);
        // If validation was failed, return the errors
        if ($validation->fails()) {
            echo json_encode(["status" => "faild"]);
            exit;
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
            echo json_encode(["status" => "ok"]);
        } else {
            echo json_encode(["status" => "faild"]);
        }
    }



    /**
     * Update the post in the database and redirect to the posts page
     * @return bool
     */
    public function updatePost()
    {
        try {
            $this->permission();
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(["status" => "faild"]);
            exit;
        }
        // Get the id and title and body and status from the post request
        $id = $_POST['id'];
        $title = $_POST['title'];
        $body = $_POST['body'];
        $status = $_POST['status'];
        // Update the post in the database
        if ($this->postsModel->updatePost($id, $title, $body, $status)) {
            echo json_encode(["status" => "ok"]);
        } else {
            echo json_encode(["status" => "faild"]);
        }
    }

    /**
     * Delete the post from the database and redirect to the posts page
     * @return void
     */
    public function deletePost()
    {
        try {
            $this->permission();
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(["status" => "faild"]);
            exit;
        }
        // Get the id from the get request
        $id = $_GET['id'];
        // Delete the post from the database
        if ($this->postsModel->deletePost($id)) {
            echo json_encode(["status" => "ok"]);
        } else {
            echo json_encode(["status" => "faild"]);
        }
    }

    private function permission()
    {
        if (!AuthController::isLoggedIn()) {
            throw new Exception();
        }
    }
}

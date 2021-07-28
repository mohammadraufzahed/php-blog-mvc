<?php

namespace SimplePHPFramework\controllers;

use SimplePHPFramework\kernel\View;
use Rakit\Validation\Validator;
use SimplePHPFramework\models\PostsModel;
use SimplePHPFramework\models\AuthModel;

require __DIR__ . "/../vendor/autoload.php";


class PostController
{
    private View $viewEngine;
    private PostsModel $postsModel;
    private AuthModel $authModel;

    public function __construct()
    {
        // Initialize the viewEngine
        $this->viewEngine = new View;
        // Initialize the postsModel
        $this->postsModel = new PostsModel();
        // Initialize the authModel
        $this->authModel = new AuthModel();
    }

    /**
     * Return the posts page and the posts list from the database
     * @return string
     */
    public function posts()
    {
        $this->permission();
        $allPosts = $this->postsModel->fetchAllPosts();
        $this->viewEngine->render("dashboard/posts/form/index.pug", [
            "allPosts" => $allPosts
        ]);
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

    public function newPost()
    {
        $this->permission();
        return $this->viewEngine->render('dashboard/posts/new/index.pug');
    }

    /**
     * Edit the post and save the changes in the database
     * @param int|string $id
     * @param string $title
     * @param string $body
     * @param string $status
     * @return bool
     */
    public function editPost()
    {
        $this->permission();
        // Get the post id from the GET request
        $postId = $_GET['id'];
        // Fetch the single post from the database
        $post = $this->postsModel->fetchPost($postId);
        // If the post was found, render the edit form else redirect to the posts page
        if ($post) {
            $this->viewEngine->render('dashboard/posts/edit/index.pug', [
                "post" => $post
            ]);
        } else {
            $this->viewEngine->redirect("/dashboard/posts");
        }
    }
    /**
     * Update the post in the database and redirect to the posts page
     * @return bool
     */
    public function updatePost()
    {
        $this->permission();
        // Get the id and title and body and status from the post request
        $id = $_POST['id'];
        $title = $_POST['title'];
        $body = $_POST['body'];
        $status = $_POST['status'];
        // Update the post in the database
        if ($this->postsModel->updatePost($id, $title, $body, $status)) {
            $this->viewEngine->redirect("/dashboard/posts");
        }
    }

    /**
     * Delete the post from the database and redirect to the posts page
     * @return void
     */
    public function deletePost()
    {
        $this->permission();
        // Get the id from the get request
        $id = $_GET['id'];
        // Delete the post from the database
        if ($this->postsModel->deletePost($id)) {
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

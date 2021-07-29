<?php

namespace SimplePHPFramework\controllers;

use SimplePHPFramework\models\UsersModel;
use SimplePHPFramework\kernel\View;
use Rakit\Validation\Validator;

require __DIR__ . "/../vendor/autoload.php";


class UsersController
{
    private View $viewEngine;
    private UsersModel $usersModel;

    public function __construct()
    {
        // Initialize the viewEngine
        $this->viewEngine = new View();
        // Initialize the Users Model
        $this->usersModel = new UsersModel();
    }

    /**
     * Render the users form
     */
    public function index()
    {
        // Check the permission
        $this->permission();
        $allUsers = $this->usersModel->fetchAllUsers();
        $this->viewEngine->render("dashboard/users/home/index.pug", ['allUsers' => $allUsers]);
    }

    /**
     * Delete the user from the database
     * @return void
     */
    public function delete()
    {
        // Check the permission
        $this->permission();
        // Get the user id from the get request
        $user_id = $_GET['id'];
        // Delete the user from the database
        $this->usersModel->deleteUser($user_id);
        // Redirect to the dashboard
        $this->viewEngine->redirect("/dashboard/users");
    }

    /**
     * Render the user edit page
     * @return void
     */
    public function edit()
    {
        // Check the permission
        $this->permission();
        // Get the user id from the get request
        $user_id = $_GET['id'];
        // Get the user from the database
        $user = $this->usersModel->getUser($user_id);
        // Render the edit page
        $this->viewEngine->render("dashboard/users/edit/index.pug", ['user' => $user]);
    }

    /**
     * Update the User in the database and redirect to the users page
     * @return void
     */
    function update()
    {
        // Check the permission
        $this->permission();
        // Get the username and email from the post request
        $username = $_POST['username'];
        $email = $_POST['email'];
        $id = $_POST['id'];
        $this->usersModel->updateUser($id, $username, $email);
        // Redirect to the dashboard
        $this->viewEngine->redirect('/dashboard/users');
    }


    /**
     * Add the new user page
     * @return void
     */
    public function new():  void
    {
        $this->viewEngine->render('dashboard/users/new/index.pug');
    }

    /**
     * Add the new user to the database and redirect to the users page
     * @return void
     */
    public function newAction(): void
    {
        // Verify the permission
        $this->permission();
        // Verify the data
        $validator = new Validator();
        $validation = $validator->make($_POST, [
            'username' => 'required',
            'password' => 'required|min:6',
            'email' => 'required|email'
        ]);
        $validation->validate();
        if ($validation->fails()) {
            $errors = $validation->errors();
            dd($errors);
            exit;
        }
        // Get the username and email and password from the POST request
        $username = $_POST["username"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $email = $_POST["email"];

        if($this->usersModel->addUser($username, $password, $email))
        {
            $this->viewEngine->redirect("/dashboard/users");
        }
    }

    /**
     * Check the session if user was not admin and was not logged in redirect to dashboard page 
     */
    public function permission()
    {
        // Start the session
        session_start();
        // Check if the user is admin
        if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] == false) {
            $this->viewEngine->redirect("/dashboard");
        }
    }
}

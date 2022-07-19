<?php

namespace SimplePHPFramework\controllers;

use Exception;
use SimplePHPFramework\models\UsersModel;
use SimplePHPFramework\models\AuthModel;

use Rakit\Validation\Validator;

require __DIR__ . "/../vendor/autoload.php";

/**
 * Class UsersController
 * @package SimplePHPFramework\controllers
 */

class UsersController
{

    private UsersModel $usersModel;
    private AuthModel $authModel;

    public function __construct()
    {
        // Initialize the Users Model
        $this->usersModel = new UsersModel();
        // Initialize the Auth Model
        $this->authModel = new AuthModel();
    }

    /**
     * Delete the user from the database
     * @return void
     */
    public function delete()
    {
        // Check the permission
        try {
            $this->permission();
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(["status" => "unauthorized"]);
            exit;
        }
        // Get the user id from the get request
        $user_id = $_GET['id'];
        // Delete the user from the database
        if ($this->usersModel->deleteUser($user_id)) {
            echo json_encode(["status" => "ok"]);
        } else {
            echo json_encode(["status" => "faild"]);
        }
    }

    /**
     * Update the User in the database and redirect to the users page
     * @return void
     */
    public function update()
    {
        // Check the permission
        try {
            $this->permission();
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(["status" => "unauthorized"]);
            exit;
        }
        // Get the username and email from the post request
        $username = $_POST['username'];
        $email = $_POST['email'];
        $id = $_POST['id'];
        if ($this->usersModel->updateUser($id, $username, $email)) {
            echo json_encode(["status" => "ok"]);
        } else {
            echo json_encode(["status" => "faild"]);
        }
    }


    /**
     * Add the new user to the database and redirect to the users page
     * @return void
     */
    public function newAction(): void
    {
        // Verify the permission
        try {
            $this->permission();
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(["status" => "unauthorized"]);
            exit;
        }
        // Verify the data
        $validator = new Validator();
        $validation = $validator->make($_POST, [
            'username' => 'required',
            'password' => 'required|min:6',
            'email' => 'required|email'
        ]);
        $validation->validate();
        if ($validation->fails()) {
            echo json_encode(["status" => "faild"]);
            exit;
        }
        // Get the username and email and password from the POST request
        $username = $_POST["username"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $email = $_POST["email"];

        if ($this->usersModel->addUser($username, $password, $email)) {
            echo json_encode(["status" => "ok"]);
        } else {
            echo json_encode(["status" => "faild"]);
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
            throw new Exception();
        }
    }
}

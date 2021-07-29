<?php

namespace SimplePHPFramework\controllers;

require __DIR__ . "/../vendor/autoload.php";

use Rakit\Validation\Validator;
use SimplePHPFramework\kernel\View;
use SimplePHPFramework\models\AuthModel;

class AuthController
{
    private View $viewEngine;
    private AuthModel $authModel;
    public function __construct()
    {
        $this->viewEngine = new View;
        $this->authModel = new AuthModel();
    }
    public function login()
    {
        $this->permission();
        $this->viewEngine->render("auth/login/index.pug", [
            "title" => "PHP MVC Blog | Login"
        ]);
    }

    public function loginRequest()
    {
        $this->permission();
        // Validate the Data
        $validator = new Validator();
        $validation = $validator->make($_POST, [
            'username' => 'required',
            'password' => 'required|min:6'
        ]);
        $validation->validate();
        if ($validation->fails()) {
            $errors = $validation->errors();
            dd($errors);
            exit;
        }
        $username = $_POST["username"];
        $password = $_POST["password"];
        // Select the user
        $userData = $this->authModel->getUser($username);
        if (password_verify($password, $userData->password)) {
            session_start();
            $_SESSION["username"] = $username;
            $_SESSION["isLoggedIn"] = true;
            $_SESSION["isAdmin"] = ($userData->is_admin == "Y") ? true : false;
            header("location: /dashboard");
        } else {
            echo "Something goes wrong or user not found";
        }
    }

    public function signup()
    {
        $this->permission();
        $this->viewEngine->render("auth/signup/index.pug", [
            "title" => "PHP MVC Blog | Signup"
        ]);
    }
    public function signupRequest()
    {
        $this->permission();
        // Validate the inputs
        $validator = new Validator();
        $validation = $validator->make($_POST, [
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'passwordConfirm' => 'required|same:password'
        ]);
        $validation->validate();
        if (!$validation->fails()) {
            $username = $_POST["username"];
            $email = $_POST["email"];
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        } else {
            $erros = $validation->errors();
            echo "<pre>";
            var_dump($erros->firstOfAll());
            echo "</pre>";
        }
        $userSignup = $this->authModel->signupUser($username, $email, $password);
        if (!$userSignup) {
        } else {
            session_start();
            $_SESSION["username"] = $username;
            $_SESSION["isLoggedIn"] = true;
            $_SESSION["isAdmin"] = false;
            header("location: /dashboard");
        }
    }

    public function logout()
    {
        if ($this::isLoggedIn()) {
            session_start();
            session_destroy();
            $_SESSION = [];
            header("location: /");
            exit;
        } else {
            header("location: /");
            exit;
        }
    }

    public static function isAdmin(): bool
    {
        session_start();
        return $_SESSION['isAdmin'] ?? false;
    }
    public static function isLoggedIn(): bool
    {
        session_start();
        return $_SESSION["isLoggedIn"] ? true : false;
    }

    public function permission()
    {
        if ($this::isLoggedIn()) {
            header("location: /");
            exit;
        }
    }
}

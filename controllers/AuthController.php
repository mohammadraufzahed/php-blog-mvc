<?php

namespace SimplePHPFramework\controllers;

require __DIR__ . "/../vendor/autoload.php";

use PDO;
use SimplePHPFramework\kernel\Database;
use Rakit\Validation\Validator;
use SimplePHPFramework\kernel\View;

class AuthController
{
    private View $viewEngine;
    private Database $db;

    public function __construct()
    {
        $this->viewEngine = new View;
        $this->db = new Database();
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
            echo "<pre>";
            var_dump($errors);
            echo "</pre>";
            exit;
        }
        $username = $_POST["username"];
        $password = $_POST["password"];
        // Select the user
        $this->db->query("SELECT password, is_admin FROM users WHERE username=:username");
        $this->db->bind(":username", $username, PDO::PARAM_STR);
        if ($this->db->execute() && $this->db->rowEffected() == 1) {
            $userData = $this->db->fetchAsObject();
            if (password_verify($password, $userData->password)) {
                $_SESSION["username"] = $username;
                $_SESSION["isLoggedIn"] = true;
                $_SESSION["isAdmin"] = ($userData->is_admin == "Y") ? true : false;
                header("location: /dashboard");
            }
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
        // Check the user exists or not
        $this->db->query("SELECT * FROM users WHERE username=:username");
        $this->db->bind(":username", $username, PDO::PARAM_STR);
        $this->db->execute();
        if ($this->db->rowEffected() == 1) {
            echo "User Exists";
            exit;
        }
        // Add the user
        $this->db->query("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $this->db->bind(":username", $username, PDO::PARAM_STR);
        $this->db->bind(":email", $email, PDO::PARAM_STR);
        $this->db->bind(":password", $password, PDO::PARAM_STR);
        if (!$this->db->execute()) {
            echo "Something goes wrong";
        } else {
            $_SESSION["username"] = $username;
            $_SESSION["isLoggedIn"] = true;
            $_SESSION["isAdmin"] = false;
            header("location: /dashboard");
        }
    }

    public static function isLoggedIn(): bool
    {
        session_start();
        if ($_SESSION["isLoggedIn"] == true) {
            return true;
        } else {
            return false;
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

    public function permission()
    {
        if ($this::isLoggedIn()) {
            header("location: /");
            exit;
        }
    }
}

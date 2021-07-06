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
        $this->viewEngine->render("auth/login/index.pug", [
            "title" => "PHP MVC Blog | Login"
        ]);
    }

    public function loginRequest()
    {
    }

    public function signup()
    {
        $this->viewEngine->render("auth/signup/index.pug", [
            "title" => "PHP MVC Blog | Signup"
        ]);
    }
    public function signupRequest()
    {
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
}

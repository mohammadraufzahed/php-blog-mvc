<?php

namespace SimplePHPFramework\controllers;

require __DIR__ . "/../vendor/autoload.php";

use SimplePHPFramework\kernel\View;

class AuthController
{
    private View $viewEngine;

    public function __construct()
    {
        $this->viewEngine = new View;
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
        $this->viewEngine->render("auth/signup/index.pug");
    }
    public function signupRequest()
    {
    }
}

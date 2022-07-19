<?php

namespace SimplePHPFramework\controllers;

require __DIR__ . "/../vendor/autoload.php";

use Exception;
use Rakit\Validation\Validator;

use SimplePHPFramework\models\AuthModel;

class AuthController
{
  private AuthModel $authModel;
  public function __construct()
  {

    $this->authModel = new AuthModel();
  }

  public function loginRequest()
  {
    try {
      $this->permission();
    } catch (Exception $e) {
      http_response_code(401);
      echo json_encode(["status" => "unauthorized"]);
      exit;
    }
    // Validate the Data
    $validator = new Validator();
    $validation = $validator->make($_POST, [
      'username' => 'required',
      'password' => 'required'
    ]);
    $validation->validate();
    if ($validation->fails()) {
      echo json_encode(["status" => "faild"]);
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
      echo json_encode(["status" => "ok"]);
    } else {
      echo json_encode(["status" => "faild"]);
    }
  }

  public function signupRequest()
  {
    try {
      $this->permission();
    } catch (Exception $e) {

      http_response_code(401);
      echo json_encode(["status" => "unauthorized"]);
      exit;
    }
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
      echo json_encode(["status" => "faild"]);
      exit;
    }
    $userSignup = $this->authModel->signupUser($username, $email, $password);
    if (!$userSignup) {
    } else {
      session_start();
      $_SESSION["username"] = $username;
      $_SESSION["isLoggedIn"] = true;
      $_SESSION["isAdmin"] = false;
      echo json_encode(["status" => "ok"]);
    }
  }

  public function logout(): void
  {
    if ($this::isLoggedIn()) {
      session_start();
      session_destroy();
      $_SESSION = [];
      echo json_encode(['status' => 'ok']);
    } else {
      echo json_encode(['status' => 'faild']);
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
    if (array_key_exists("isLoggedIn", $_SESSION)) {
      return (bool)$_SESSION["isLoggedIn"];
    } else {
      return false;
    }
  }

  public function permission()
  {
    if ($this::isLoggedIn()) {
      throw new Exception("");
    }
  }
}

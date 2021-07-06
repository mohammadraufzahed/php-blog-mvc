<?php

namespace SimplePHPFramework\models;

use SimplePHPFramework\kernel\Database;
use PDO;

require __DIR__ . "/../vendor/autoload.php";

class AuthModel
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getUser(string $username): object
    {
        $this->db->query("SELECT password, is_admin FROM users WHERE username=:username");
        $this->db->bind(":username", $username, PDO::PARAM_STR);
        if ($this->db->execute() && $this->db->rowEffected() == 1) {
            return $this->db->fetchAsObject();
        }
    }

    public function signupUser(string $username, string $email, string $password): bool
    {
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
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

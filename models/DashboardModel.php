<?php

namespace SimplePHPFramework\models;

use SimplePHPFramework\kernel\Database;
use PDO;

require __DIR__ . "/../vendor/autoload.php";

class DashboardModel
{
    private Database $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function signupDate(string $username)
    {
        $this->db->query("select created_at FROM users WHERE username=:username ");
        $this->db->bind(":username", $username, PDO::PARAM_STR);
        if ($this->db->execute()) {
            return $this->db->fetchAsObject();
        }
    }

    public function countPosts(): object
    {
        $this->db->query("SELECT COUNT(*) countPost FROM posts");
        if ($this->db->execute()) {
            return $this->db->fetchAsObject();
        }
    }

    public function countUser(): object
    {
        $this->db->query("SELECT COUNT(*) countUser FROM users");
        if ($this->db->execute()) {
            return $this->db->fetchAsObject();
        }
    }
}

<?php

namespace SimplePHPFramework\models;

use http\Params;
use SimplePHPFramework\kernel\Database;
use PDO;

require __DIR__ . "/../vendor/autoload.php";

class DashboardModel
{
    private Database $db;

    /**
     * DashboardModel constructor.
     */
    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Calculate the signup Date
     * @param string $username
     * @return string
     */
    public function signupDate(string $username)
    {
        $this->db->query("select created_at FROM users WHERE username=:username ");
        $this->db->bind(":username", $username, PDO::PARAM_STR);
        if ($this->db->execute()) {
            return $this->db->fetchAsObject()->created_at ?? "";
        }
    }

    /**
     * Count the posts
     * @return object
     */
    public function countPosts(): object
    {
        $this->db->query("SELECT COUNT(*) countPost FROM posts");
        if ($this->db->execute()) {
            return $this->db->fetchAsObject();
        }
    }

    /**
     * Count the users
     * @return object
     */
    public function countUser(): object
    {
        $this->db->query("SELECT COUNT(*) countUser FROM users");
        if ($this->db->execute()) {
            return $this->db->fetchAsObject();
        }
    }

    /**
     * Get the weblog Settings
     * @return object|null
     */
    public function getSettings(): object|null
    {
        $this->db->query("SELECT * FROM settings");
        if ($this->db->execute()) {
            return $this->db->fetchAsObject();
        } else {
            return null;
        }
    }

    /**
     * Set the weblog Settings
     * @param string $title
     * @param string $description
     * @param string $author
     * @param string $authorInfo
     * @return bool
     */
    public function setSettings(string $title, string $description, string $author, string $authorInfo): bool
    {
       // Prepare the sql
        $this->db->query("UPDATE settings SET blogTitle = :blogTitle, blogDescription = :blogDescription, blogAuthor = :blogAuthor, blogAuthorInfo = :blogAuthorInfo");
        $this->db->bind(":blogTitle", $title, PDO::PARAM_STR);
        $this->db->bind(":blogDescription", $description, PDO::PARAM_STR);
        $this->db->bind(":blogAuthor", $author, PDO::PARAM_STR);
        $this->db->bind(":blogAuthorInfo", $authorInfo, PDO::PARAM_STR);
        if($this->db->execute())
        {
            return true;
        } else {
            return false;
        }
    }
}

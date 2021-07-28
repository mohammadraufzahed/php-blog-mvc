<?php

namespace SimplePHPFramework\models;

use SimplePHPFramework\kernel\Database;
use PDO;

require __DIR__ . "/../vendor/autoload.php";

class PostsModel
{
    private Database $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function fetchAllPosts(): array
    {
        $this->db->query("SELECT * FROM posts");
        if ($this->db->execute()) {
            $allPosts =  $this->db->fetchAllAsObject();
            foreach ($allPosts as $key => $value) {
                $value->user_id = $this->fetchWriter($value->user_id)->username;
            }
            return $allPosts;
        }
    }

    /**
     * Fetch a single post from the database
     * @param int $id
     * @return object
     */
    public function fetchPost(int $id): object
    {
        // Prepare the query and bind the data to the query
        $this->db->query("SELECT * FROM posts WHERE id = :id");
        $this->db->bind(":id", $id, PDO::PARAM_INT);
        // Execute the query
        $this->db->execute();
        // Return the result as an object
        return $this->db->fetchAsObject();
    }
    public function fetchWriter(int $id): object
    {
        $this->db->query("SELECT * FROM users WHERE id=:id");
        $this->db->bind("id", $id, PDO::PARAM_INT);
        if ($this->db->execute()) {
            return $this->db->fetchAsObject();
        }
    }

    /**
     * Save the post to the database
     * @param string $user_id
     * @param string $title
     * @param string $body
     * @param string $status
     * @return bool
     */
    public function savePost(string $user_id, string $title, string $body, string $status): bool
    {
        $this->db->query("INSERT INTO posts (user_id, title, body, published) VALUES (:user_id, :title, :body, :status)");
        $this->db->bind("user_id", $user_id, PDO::PARAM_INT);
        $this->db->bind("title", $title, PDO::PARAM_STR);
        $this->db->bind("body", $body, PDO::PARAM_STR);
        $this->db->bind("status", $status, PDO::PARAM_STR);
        return $this->db->execute();
    }

    /**
     * Update the post in the database
     * @param string $id
     * @param string $title
     * @param string $body
     * @param string $status
     * @return bool
     */
    public function updatePost(string $id, string $title, string $body, string $status): bool
    {
        $this->db->query("UPDATE posts SET title=:title, body=:body, published=:status WHERE id=:id");
        $this->db->bind("id", $id, PDO::PARAM_INT);
        $this->db->bind("title", $title, PDO::PARAM_STR);
        $this->db->bind("body", $body, PDO::PARAM_STR);
        $this->db->bind("status", $status, PDO::PARAM_STR);
        return $this->db->execute();
    }
}

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

    public function fetchWriter(int $id): object
    {
        $this->db->query("SELECT * FROM users WHERE id=:id");
        $this->db->bind("id", $id, PDO::PARAM_INT);
        if ($this->db->execute()) {
            return $this->db->fetchAsObject();
        }
    }
}

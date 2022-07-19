<?php

namespace SimplePHPFramework\models;

use SimplePHPFramework\kernel\Database;
use PDO;

require __DIR__ . "/../vendor/autoload.php";

class UsersModel
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Fetch all users from the database and return them as an object
     * @return array
     */
    public function fetchAllUsers()
    {
        $this->db->query("SELECT * FROM users");
        $this->db->execute();
        return $this->db->fetchAllAsObject();
    }

    /**
     * Delete the user with the given id
     * @param int $id
     * @return bool
     */
    public function deleteUser($id): bool
    {
        $this->db->query("DELETE FROM users WHERE id = :id AND is_admin = 'N'");
        $this->db->bind(":id", $id, PDO::PARAM_INT);
        return $this->db->execute();
    }

    /**
     * Get the user with the given id from the database
     * @param int $id
     * @return object
     */
    public function getUser($id): object
    {
        $this->db->query("SELECT * FROM users WHERE id = :id");
        $this->db->bind(":id", $id, PDO::PARAM_INT);
        $this->db->execute();
        return $this->db->fetchAsObject();
    }

    /**
     * Update the user informations with the given id
     * @param int $id
     * @param string $username
     * @param string $email
     * @param string $isAdmin
     * @return bool
     */
    public function updateUser($id, $username, $email): bool
    {
        $this->db->query("UPDATE users SET username = :username, email = :email WHERE id = :id");
        $this->db->bind(":id", $id, PDO::PARAM_INT);
        $this->db->bind(":username", $username, PDO::PARAM_STR);
        $this->db->bind(":email", $email, PDO::PARAM_STR);
        return $this->db->execute();
    }

    /**
     * Add the new user to the database
     * @return bool
     */
    public function addUser(string $username, string $password, string $email): bool
    {
        $this->db->query("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
        $this->db->bind(":username", $username, PDO::PARAM_STR);
        $this->db->bind(":password", $password, PDO::PARAM_STR);
        $this->db->bind(":email", $email, PDO::PARAM_STR);
        return $this->db->execute();

    }
}

<?php
require_once(__DIR__ . '/../../config/db.php');

class UserModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function findByUsername($username)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username_user = :username AND status_user = 1");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllUsers()
    {
        $stmt = $this->conn->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE user_id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($username, $password, $role)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (username_user, password_user, role_user) VALUES (:username, :password, :role)");
        return $stmt->execute([
            'username' => $username,
            'password' => $hashedPassword,
            'role' => $role
        ]);
    }

    public function updateUser($id, $username, $role)
    {
        $stmt = $this->conn->prepare("UPDATE users SET username_user = :username, role_user = :role WHERE user_id = :id");
        return $stmt->execute([
            'id' => $id,
            'username' => $username,
            'role' => $role
        ]);
    }

    public function disableUser($id)
    {
        $stmt = $this->conn->prepare("UPDATE users SET status_user = 0 WHERE user_id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function enableUser($id)
    {
        $stmt = $this->conn->prepare("UPDATE users SET status_user = 1 WHERE user_id = :id");
        return $stmt->execute(['id' => $id]);
    }
}

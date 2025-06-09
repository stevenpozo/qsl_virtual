<?php
require_once(__DIR__ . '/../../config/db.php');

class UserModel
{
    private $conn;

    // Constructor que inicializa la conexión a la base de datos.
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Busca un usuario por su nombre de usuario si está habilitado.
    public function findByUsername($username)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username_user = :username AND status_user = 1");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtiene todos los usuarios del sistema (activos o inactivos).
    public function getAllUsers()
    {
        $stmt = $this->conn->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene un usuario por su ID.
    public function getUserById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE user_id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crea un nuevo usuario con nombre, contraseña cifrada y rol.
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

    // Actualiza el nombre de usuario y el rol de un usuario existente.
    public function updateUser($id, $username, $role)
    {
        $stmt = $this->conn->prepare("UPDATE users SET username_user = :username, role_user = :role WHERE user_id = :id");
        return $stmt->execute([
            'id' => $id,
            'username' => $username,
            'role' => $role
        ]);
    }

    // Desactiva (inhabilita) un usuario por su ID.
    public function disableUser($id)
    {
        $stmt = $this->conn->prepare("UPDATE users SET status_user = 0 WHERE user_id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Activa (habilita) un usuario previamente desactivado.
    public function enableUser($id)
    {
        $stmt = $this->conn->prepare("UPDATE users SET status_user = 1 WHERE user_id = :id");
        return $stmt->execute(['id' => $id]);
    }
}

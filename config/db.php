<?php
class Database {
    private $host = "localhost";
    private $port = "3307"; 
    private $db_name = "qsl_virtual";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection(): PDO {
        $this->conn = null;

        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Error en conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>

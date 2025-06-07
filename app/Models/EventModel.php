<?php
require_once(__DIR__ . '/../../config/db.php');

class EventModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function insertarEvento($name_event, $date_event, $call_event, $image_event)
    {
        $sql = "INSERT INTO eventos (name_event, date_event, call_event, image_event) 
                VALUES (:name_event, :date_event, :call_event, :image_event)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name_event' => $name_event,
            ':date_event' => $date_event,
            ':call_event' => $call_event,
            ':image_event' => $image_event
        ]);
    }

    public function obtenerEventos()
    {
        $sql = "SELECT * FROM eventos ORDER BY date_event DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEventoPorId($id)
    {
        $sql = "SELECT * FROM eventos WHERE event_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarEvento($id, $name, $date, $call, $image, $status)
    {
        $sql = "UPDATE eventos SET 
                name_event = :name,
                date_event = :date,
                call_event = :call,
                image_event = :image,
                status_event = :status
            WHERE event_id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':date' => $date,
            ':call' => $call,
            ':image' => $image,
            ':status' => $status,
            ':id' => $id
        ]);
    }
}

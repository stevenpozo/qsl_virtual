<?php
require_once(__DIR__ . '/../../config/db.php');

class EventoModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function insertarEvento($name_event, $date_event, $call_event, $image_event) {
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
}
?>

<?php
require_once(__DIR__ . '/../../config/db.php');

class LogModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function insertarLog($event_id, $call_log, $date_log, $utc_log, $band_log, $mode_log, $rst_log) {
        $sql = "INSERT INTO logs (event_id, call_log, date_log, utc_log, band_log, mode_log, rst_log)
                VALUES (:event_id, :call_log, :date_log, :utc_log, :band_log, :mode_log, :rst_log)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':event_id' => $event_id,
            ':call_log' => $call_log,
            ':date_log' => $date_log,
            ':utc_log' => $utc_log,
            ':band_log' => $band_log,
            ':mode_log' => $mode_log,
            ':rst_log' => $rst_log
        ]);
    }
}
?>

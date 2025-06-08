<?php
require_once(__DIR__ . '/../../config/db.php');

class LogModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function insertLog($data)
    {
        $sql = "INSERT INTO logs (event_id, call_log, date_log, utc_log, time_off_log, band_log, mode_log, rst_rcvd_log, rst_sent_log, freq_log, gridsquare_log, my_gridsquare_log, station_callsign_log, comment_log, status_log)
                VALUES (:event_id, :call_log, :date_log, :utc_log, :time_off_log, :band_log, :mode_log, :rst_rcvd_log, :rst_sent_log, :freq_log, :gridsquare_log, :my_gridsquare_log, :station_callsign_log, :comment_log, :status_log)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':event_id' => $data['event_id'],
            ':call_log' => $data['call_log'],
            ':date_log' => $data['date_log'],
            ':utc_log' => $data['utc_log'],
            ':time_off_log' => $data['time_off_log'],
            ':band_log' => $data['band_log'],
            ':mode_log' => $data['mode_log'],
            ':rst_rcvd_log' => $data['rst_rcvd_log'],
            ':rst_sent_log' => $data['rst_sent_log'],
            ':freq_log' => $data['freq_log'],
            ':gridsquare_log' => $data['gridsquare_log'],
            ':my_gridsquare_log' => $data['my_gridsquare_log'],
            ':station_callsign_log' => $data['station_callsign_log'],
            ':comment_log' => $data['comment_log'],
            ':status_log' => $data['status_log'] ?? 1
        ]);
    }

    public function existsLog($event_id, $data)
    {
        $sql = "SELECT COUNT(*) FROM logs WHERE 
        event_id = :event_id AND call_log = :call_log AND date_log = :date_log 
        AND utc_log = :utc_log AND time_off_log = :time_off_log 
        AND band_log = :band_log AND rst_rcvd_log = :rst_rcvd_log 
        AND rst_sent_log = :rst_sent_log AND freq_log = :freq_log 
        AND my_gridsquare_log = :my_gridsquare_log";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':event_id' => $event_id,
            ':call_log' => $data['call_log'],
            ':date_log' => $data['date_log'],
            ':utc_log' => $data['utc_log'],
            ':time_off_log' => $data['time_off_log'],
            ':band_log' => $data['band_log'],
            ':rst_rcvd_log' => $data['rst_rcvd_log'],
            ':rst_sent_log' => $data['rst_sent_log'],
            ':freq_log' => $data['freq_log'],
            ':my_gridsquare_log' => $data['my_gridsquare_log'],
        ]);

        return $stmt->fetchColumn() > 0;
    }

    public function getLogsByEvent($eventId, $search = '', $date = '', $limit = 20, $offset = 0)
    {
        $sql = "SELECT * FROM logs WHERE event_id = :event_id";
        $params = [':event_id' => $eventId];

        if ($search !== '') {
            $sql .= " AND (call_log LIKE :search OR station_callsign_log LIKE :search)";
            $params[':search'] = "%$search%";
        }

        if ($date !== '') {
            $sql .= " AND date_log = :date_log";
            $params[':date_log'] = $date;
        }

        $sql .= " ORDER BY date_log DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        foreach ($params as $key => $val) {
            if ($key !== ':limit' && $key !== ':offset') {
                $stmt->bindValue($key, $val);
            }
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countLogsByEvent($eventId, $search = '', $date = '')
    {
        $sql = "SELECT COUNT(*) FROM logs WHERE event_id = :event_id";
        $params = [':event_id' => $eventId];

        if ($search !== '') {
            $sql .= " AND (call_log LIKE :search OR station_callsign_log LIKE :search)";
            $params[':search'] = "%$search%";
        }

        if ($date !== '') {
            $sql .= " AND date_log = :date_log";
            $params[':date_log'] = $date;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function toggleStatus($logId)
    {
        $sql = "UPDATE logs SET status_log = 1 - status_log WHERE log_id = :log_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':log_id' => $logId]);
    }

    public function getLogById($logId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM logs WHERE log_id = :log_id");
        $stmt->execute([':log_id' => $logId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateLog($data)
    {
        $sql = "UPDATE logs SET 
            call_log = :call_log,
            date_log = :date_log,
            utc_log = :utc_log,
            time_off_log = :time_off_log,
            band_log = :band_log,
            mode_log = :mode_log,
            rst_sent_log = :rst_sent_log,
            rst_rcvd_log = :rst_rcvd_log,
            freq_log = :freq_log,
            station_callsign_log = :station_callsign_log,
            my_gridsquare_log = :my_gridsquare_log,
            comment_log = :comment_log
            WHERE log_id = :log_id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function getStatsByEvent($event_id)
    {
        $stmt = $this->conn->prepare("
        SELECT 
            COUNT(*) AS total_logs,
            COUNT(DISTINCT call_log) AS unique_calls,
            MAX(date_log) AS last_date,
            (SELECT band_log FROM logs WHERE event_id = :eid GROUP BY band_log ORDER BY COUNT(*) DESC LIMIT 1) AS top_band,
            (SELECT mode_log FROM logs WHERE event_id = :eid GROUP BY mode_log ORDER BY COUNT(*) DESC LIMIT 1) AS top_mode
        FROM logs WHERE event_id = :eid
    ");
        $stmt->execute([':eid' => $event_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getLogsByEventAndCall($event_id, $call)
    {
        $sql = "SELECT * FROM logs 
            WHERE event_id = :event_id 
              AND UPPER(call_log) = :call_log 
              AND status_log = 1
            ORDER BY date_log DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':event_id' => $event_id,
            ':call_log' => strtoupper($call)
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

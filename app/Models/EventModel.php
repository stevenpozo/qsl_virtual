<?php
require_once(__DIR__ . '/../../config/db.php');

class EventModel
{
    private $conn;

    // Constructor que inicializa la conexión a la base de datos.
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Inserta un nuevo evento en la base de datos con los datos proporcionados.
    public function createEvent($data)
    {
        $sql = "INSERT INTO eventos (name_event, date_event, call_event, image_event, status_event, color_event)
            VALUES (:name_event, :date_event, :call_event, :image_event, :status_event, :color_event)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name_event' => $data['name_event'],
            ':date_event' => $data['date_event'],
            ':call_event' => $data['call_event'],
            ':image_event' => $data['image_event'],
            ':status_event' => $data['status_event'],
            ':color_event' => $data['color_event']
        ]);
    }

    // Obtiene todos los eventos ordenados por fecha descendente.
    public function getAllEvents()
    {
        $sql = "SELECT * FROM eventos ORDER BY date_event DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Recupera los datos de un evento específico por su ID.
    public function getEventById($id)
    {
        $sql = "SELECT * FROM eventos WHERE event_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Actualiza los datos de un evento existente por su ID.
    public function updateEvent($id, $name, $date, $call, $image, $status, $color)
    {
        $sql = "UPDATE eventos SET 
            name_event = :name,
            date_event = :date,
            call_event = :call,
            image_event = :image,
            status_event = :status,
            color_event = :color
        WHERE event_id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':date' => $date,
            ':call' => $call,
            ':image' => $image,
            ':status' => $status,
            ':color' => $color,
            ':id' => $id
        ]);
    }


    // Verifica si ya existe un evento con el mismo nombre (insensible a mayúsculas/minúsculas).
    public function existsCallSign($name_event)
    {
        $sql = "SELECT COUNT(*) FROM eventos WHERE LOWER(name_event) = LOWER(:name_event)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':name_event' => $name_event]);
        return $stmt->fetchColumn() > 0;
    }


    // Cuenta cuántos eventos coinciden con filtros de búsqueda por nombre/call o fecha.
    public function countFilteredEvents($search = '', $date = '')
    {
        $sql = "SELECT COUNT(*) FROM eventos WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (name_event LIKE :search OR call_event LIKE :search)";
            $params[':search'] = "%$search%";
        }
        if (!empty($date)) {
            $sql .= " AND date_event = :date";
            $params[':date'] = $date;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    // Devuelve una lista de eventos filtrados por búsqueda y/o fecha, con paginación.
    public function getFilteredEvents($search = '', $date = '', $limit = 20, $offset = 0)
    {
        $sql = "SELECT * FROM eventos WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (name_event LIKE :search OR call_event LIKE :search)";
            $params[':search'] = "%$search%";
        }
        if (!empty($date)) {
            $sql .= " AND date_event = :date";
            $params[':date'] = $date;
        }

        $sql .= " ORDER BY date_event DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteEventWithLogs($event_id)
    {
        // Eliminar logs primero (clave foránea depende de la config de tu DB)
        $sqlLogs = "DELETE FROM logs WHERE event_id = :event_id";
        $stmtLogs = $this->conn->prepare($sqlLogs);
        $stmtLogs->execute([':event_id' => $event_id]);

        // Luego eliminar el evento
        $sqlEvent = "DELETE FROM eventos WHERE event_id = :event_id";
        $stmtEvent = $this->conn->prepare($sqlEvent);
        return $stmtEvent->execute([':event_id' => $event_id]);
    }
}

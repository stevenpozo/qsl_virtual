<?php
require_once(__DIR__ . '/../Models/LogModel.php');
require_once(__DIR__ . '/../../config/constants.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['log_file'])) {
    $eventId = $_POST['event_id'];
    $archivo = $_FILES['log_file']['tmp_name'];
    $extension = pathinfo($_FILES['log_file']['name'], PATHINFO_EXTENSION);

    if (!in_array(strtolower($extension), ['csv', 'txt'])) {
        header("Location: " . BASE_URL . "/index.php?view=admin/logs/load_log&msg=Formato no válido. Solo se permite CSV o TXT.");
        exit;
    }

    $model = new LogModel();
    $duplicados = 0;
    $insertados = 0;

    if (($handle = fopen($archivo, "r")) !== false) {
        $header = fgetcsv($handle, 1000, ";"); // Ignorar encabezado

        while (($row = fgetcsv($handle, 1000, ";")) !== false) {
            if (count($row) < 10) continue;

            $data = [
                'event_id' => $eventId,
                'band_log' => trim($row[0]),
                'call_log' => trim($row[1]),
                'freq_log' => trim($row[2]),
                'mode_log' => trim($row[3]),
                'rst_rcvd_log' => trim($row[4]),
                'rst_sent_log' => trim($row[5]),
                'station_callsign_log' => trim($row[6]),
                'time_off_log' => formatTime($row[7]),
                'utc_log' => formatTime($row[8]),
                'date_log' => formatDate($row[9]),
                'status_log' => 1
            ];

            if (!$model->existsLog($eventId, $data)) {
                if ($model->insertLog($data)) {
                    $insertados++;
                }
            } else {
                $duplicados++;
            }
        }

        fclose($handle);
    }

    $message = "✅ $insertados logs insertados correctamente.";
    if ($duplicados > 0) $message .= " ⚠️ $duplicados registros duplicados fueron omitidos.";

    header("Location: " . BASE_URL . "/index.php?view=admin/events/list_events&msg=" . urlencode($message));
    exit;
}

/**
 * Convierte una fecha YYYYMMDD a YYYY-MM-DD
 */
function formatDate($date)
{
    return strlen($date) === 8
        ? substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2)
        : $date;
}

/**
 * Convierte una hora HHMMSS a HH:MM:SS
 */
function formatTime($time)
{
    return strlen($time) === 6
        ? substr($time, 0, 2) . ':' . substr($time, 2, 2) . ':' . substr($time, 4, 2)
        : $time;
}

<?php
require_once(__DIR__ . '/../Models/LogModel.php');

// Este bloque principal gestiona la carga de un archivo .ADI con logs de contactos.
// Extrae datos de cada línea con <EOR>, evita duplicados y los inserta en la base.

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['adi_file'])) {
    $eventId = $_POST['event_id'];
    $archivo = $_FILES['adi_file']['tmp_name'];

    $lines = file($archivo);
    $model = new LogModel();

    $duplicados = 0;
    $insertados = 0;

    foreach ($lines as $line) {
        if (strpos($line, '<EOR>') !== false) {
            $data = [
                'call_log' => extractData($line, 'CALL'),
                'date_log' => formatDate(extractData($line, 'QSO_DATE')),
                'utc_log' => formatTime(extractData($line, 'TIME_ON')),
                'time_off_log' => formatTime(extractData($line, 'TIME_OFF')),
                'band_log' => extractData($line, 'BAND'),
                'mode_log' => extractData($line, 'MODE'),
                'rst_rcvd_log' => extractData($line, 'RST_RCVD'),
                'rst_sent_log' => extractData($line, 'RST_SENT'),
                'freq_log' => extractData($line, 'FREQ'),
                'gridsquare_log' => extractData($line, 'GRIDSQUARE'),
                'my_gridsquare_log' => extractData($line, 'MY_GRIDSQUARE'),
                'station_callsign_log' => extractData($line, 'STATION_CALLSIGN'),
                'comment_log' => extractData($line, 'COMMENT'),
                'status_log' => 1,
                'event_id' => $eventId
            ];

            if ($data['call_log'] && $data['date_log'] && $data['utc_log']) {
                if (!$model->existsLog($eventId, $data)) {
                    if ($model->insertLog($data)) {
                        $insertados++;
                    }
                } else {
                    $duplicados++;
                }
            }
        }
    }

    $message = "✅ $insertados logs inserted.";
    if ($duplicados > 0) {
        $message .= " ⚠️ $duplicados duplicates were skipped.";
    }

    header("Location: /qsl_virtual/public/index.php?view=admin/events/list_events&msg=" . urlencode($message));
    exit;
}

// Extrae el valor de un campo específico en una línea ADIF.
// Ej: extrae el valor de <CALL:6>EA8AAK como "EA8AAK".
function extractData($line, $tag) {
    $pattern = "/<$tag:\d+>([^<]*)/";
    return (preg_match($pattern, $line, $matches)) ? trim($matches[1]) : null;
}

// Formatea una fecha ADIF tipo "20240608" a formato SQL "2024-06-08".
function formatDate($date) {
    return $date ? substr($date, 0, 4) . "-" . substr($date, 4, 2) . "-" . substr($date, 6, 2) : null;
}

// Formatea la hora ADIF tipo "154030" a formato "15:40:30".
// Si ya tiene formato, lo conserva.
function formatTime($hhmmss) {
    return (strlen($hhmmss) === 6)
        ? substr($hhmmss, 0, 2) . ':' . substr($hhmmss, 2, 2) . ':' . substr($hhmmss, 4, 2)
        : $hhmmss;
}

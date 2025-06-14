<?php
require_once(__DIR__ . '/../Models/LogModel.php');
require_once(__DIR__ . '/../../config/constants.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['event_id']) || !isset($data['logs']) || !is_array($data['logs'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

$eventId = $data['event_id'];
$logs = $data['logs'];
$model = new LogModel();

$response = [];

foreach ($logs as $index => $log) {
    $normalized = [
        'event_id' => $eventId,
        'band_log' => $log['BAND'] ?? '',
        'call_log' => $log['CALL'] ?? '',
        'freq_log' => $log['FREQ'] ?? '',
        'mode_log' => $log['MODE'] ?? '',
        'rst_rcvd_log' => $log['RST_RCVD'] ?? '',
        'rst_sent_log' => $log['RST_SENT'] ?? '',
        'station_callsign_log' => $log['STATION_CALLSIGN'] ?? '',
        'time_off_log' => formatTime($log['TIME_OFF'] ?? ''),
        'utc_log' => formatTime($log['TIME_ON'] ?? ''),
        'date_log' => formatDate($log['QSO_DATE'] ?? ''),
        'status_log' => 1
    ];

    $isDuplicate = $model->existsLog($eventId, $normalized);

    $response[] = [
        'index' => $index,
        'isDuplicate' => $isDuplicate
    ];
}


echo json_encode($response);

function formatDate($date)
{
    return strlen($date) === 8
        ? substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2)
        : $date;
}

function formatTime($time)
{
    return strlen($time) === 6
        ? substr($time, 0, 2) . ':' . substr($time, 2, 2) . ':' . substr($time, 4, 2)
        : $time;
}

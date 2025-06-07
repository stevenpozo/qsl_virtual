<?php
// Rutas permitidas
$rutas_validas = [
    'admin/events/list_events' => 'app/Views/admin/events/list_events.php',
    'admin/events/crear_evento' => 'app/Views/admin/events/crear_evento.php',
    'admin/events/edit_event' => 'app/Views/admin/events/edit_event.php',
    'admin/logs/load_log' => 'app/Views/admin/logs/load_log.php',
    'admin/logs/list_logs' => 'app/Views/admin/logs/list_logs.php',
    'admin/logs/edit_log' => 'app/Views/admin/logs/edit_log.php',
    'admin/statistics/statistics_events' => 'app/Views/admin/statistics/statistics_events.php',
    'public/search_qsl' => 'app/Views/public/search_qsl.php',
    'public/list_qsls' => 'app/Views/public/list_qsls.php',


];

$view = $_GET['view'] ?? 'admin/events/list_events';
$action = $_GET['action'] ?? null;

// Incluir modelos necesarios
require_once(__DIR__ . '/../app/Models/EventModel.php');
require_once(__DIR__ . '/../app/Models/LogModel.php');

$model = new LogModel();

// ✅ Acción: crear evento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create_event') {
    require_once(__DIR__ . '/../app/Controllers/EventController.php');
    $controller = new EventController();
    $controller->createEvent();
    exit;
}

// ✅ Acción: editar evento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_event'])) {
    require_once(__DIR__ . '/../app/Controllers/EventController.php');
    $controller = new EventController();
    $controller->editEvent();
    exit;
}

// ✅ Acción: insertar log manual
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'insert_manual_log') {
    $data = [
        'event_id' => $_POST['event_id'],
        'call_log' => $_POST['call_log'],
        'date_log' => $_POST['date_log'],
        'utc_log' => $_POST['utc_log'],
        'time_off_log' => $_POST['utc_log'],
        'band_log' => $_POST['band_log'] ?? '',
        'mode_log' => $_POST['mode_log'] ?? '',
        'rst_sent_log' => $_POST['rst_sent_log'] ?? '',
        'rst_rcvd_log' => $_POST['rst_rcvd_log'] ?? '',
        'freq_log' => $_POST['freq_log'] ?? '',
        'gridsquare_log' => '',
        'my_gridsquare_log' => $_POST['my_gridsquare_log'] ?? '',
        'station_callsign_log' => $_POST['station_callsign_log'] ?? '',
        'comment_log' => $_POST['comment_log'] ?? '',
        'status_log' => 1
    ];

    if (!$model->existsLog($data['event_id'], $data)) {
        $model->insertLog($data);
        $msg = "✅ Log insertado correctamente.";
    } else {
        $msg = "⚠️ El log ya existe y no se insertó.";
    }

    header("Location: index.php?view=admin/logs/list_logs&event_id={$data['event_id']}&msg=" . urlencode($msg));
    exit;
}

// ✅ Acción: cambiar estado del log
if ($action === 'toggle_log_status' && isset($_GET['log_id'], $_GET['event_id'])) {
    $model->toggleStatus($_GET['log_id']);
    header("Location: index.php?view=admin/logs/list_logs&event_id=" . $_GET['event_id']);
    exit;
}

// ✅ Acción: actualizar log (formulario de edición)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update_log') {
    $data = [
        'log_id' => $_POST['log_id'],
        'call_log' => $_POST['call_log'],
        'date_log' => $_POST['date_log'],
        'utc_log' => $_POST['utc_log'],
        'time_off_log' => $_POST['time_off_log'],
        'band_log' => $_POST['band_log'],
        'mode_log' => $_POST['mode_log'],
        'rst_sent_log' => $_POST['rst_sent_log'],
        'rst_rcvd_log' => $_POST['rst_rcvd_log'],
        'freq_log' => $_POST['freq_log'],
        'station_callsign_log' => $_POST['station_callsign_log'],
        'my_gridsquare_log' => $_POST['my_gridsquare_log'],
        'comment_log' => $_POST['comment_log']
    ];

    $model->updateLog($data);

    header("Location: index.php?view=admin/logs/list_logs&event_id=" . $_POST['event_id'] . "&msg=" . urlencode("✅ Log actualizado correctamente."));
    exit;
}

if ($view === 'admin/events/statistics_events') {
    require_once(__DIR__ . '/../app/Controllers/EventController.php');
    $controller = new EventController();
    $controller->showStatistics();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'buscar_qsls') {
    require_once(__DIR__ . '/../app/Models/LogModel.php');
    $model = new LogModel();

    $event_id = $_POST['event_id'];
    $call = strtoupper(trim($_POST['call']));

    $logs = $model->getLogsByEventAndCall($event_id, $call);

    if (count($logs) === 0) {
        header("Location: index.php?view=public/search_qsl&msg=" . urlencode("❌ Record not found for CALL '$call'"));
        exit;
    }

    // Pasar logs a la vista
    $_SESSION['qsls'] = $logs;
    $_SESSION['call'] = $call;
    $_SESSION['event_id'] = $event_id;

    header("Location: index.php?view=public/list_qsls");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'search_logs') {
    require_once(__DIR__ . '/../app/Controllers/QslController.php');
    $controller = new QslController();
    $controller->searchLogs();
    exit;
}

if ($action === 'generate_qsl_diploma') {
    require_once(__DIR__ . '/../app/Controllers/QslController.php');
    $controller = new QslController();
    $controller->generateQslDiploma();
    exit;
}



// ✅ Mostrar vista correspondiente (AL FINAL)
if (array_key_exists($view, $rutas_validas)) {
    require_once(__DIR__ . '/../' . $rutas_validas[$view]);
} else {
    echo "<h2>❌ Vista no encontrada</h2>";
}

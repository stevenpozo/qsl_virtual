<?php
session_start();

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
    'admin/management/login' => 'app/Views/admin/management/login.php',
    'admin/management/dashboard' => 'app/Views/admin/management/dashboard.php',
    'admin/management/users' => 'app/Views/admin/management/users.php',
    'admin/management/edit_user' => 'app/Views/admin/management/edit_user.php',
    'admin/management/create_user' => 'app/Views/admin/management/create_user.php',
];

$view = $_GET['view'] ?? 'public/search_qsl';
$action = $_GET['action'] ?? null;

// Incluir modelos necesarios
require_once(__DIR__ . '/app/Models/EventModel.php');
require_once(__DIR__ . '/app/Models/LogModel.php');
$model = new LogModel();

// ✅ Acción: crear evento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create_event') {
    require_once(__DIR__ . '/app/Controllers/EventController.php');
    (new EventController())->createEvent();
    exit;
}

// ✅ Acción: editar evento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_event'])) {
    require_once(__DIR__ . '/app/Controllers/EventController.php');
    (new EventController())->editEvent();
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

// ✅ Ver estadísticas
if ($view === 'admin/events/statistics_events') {
    require_once(__DIR__ . '/app/Controllers/EventController.php');
    (new EventController())->showStatistics();
    exit;
}

// ✅ Buscar QSLs (vista pública)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'buscar_qsls') {
    $event_id = $_POST['event_id'];
    $call = strtoupper(trim($_POST['call']));
    $logs = $model->getLogsByEventAndCall($event_id, $call);

    if (count($logs) === 0) {
        header("Location: index.php?view=public/search_qsl&msg=" . urlencode("❌ Record not found for CALL '$call'"));
        exit;
    }

    $_SESSION['qsls'] = $logs;
    $_SESSION['call'] = $call;
    $_SESSION['event_id'] = $event_id;
    header("Location: index.php?view=public/list_qsls");
    exit;
}

// ✅ Buscar logs QSL (desde cliente)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'search_logs') {
    require_once(__DIR__ . '/app/Controllers/QslController.php');
    (new QslController())->searchLogs();
    exit;
}

// ✅ Generar diploma PDF
if ($action === 'generate_single_qsl_diploma') {
    require_once(__DIR__ . '/app/Controllers/QslController.php');
    (new QslController())->generateSingleQslDiploma();
    exit;
}



// ✅ Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'login') {
    require_once(__DIR__ . '/app/Models/UserModel.php');

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $userModel = new UserModel();
    $user = $userModel->findByUsername($username);

    if ($user && password_verify($password, $user['password_user'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username_user'];
        $_SESSION['role'] = $user['role_user'];
        header('Location: index.php?view=admin/management/dashboard');
    } else {
        header('Location: index.php?view=admin/management/login&error=Credenciales incorrectas');
    }
    exit;
}

// ✅ Logout
if ($action === 'logout') {
    session_destroy();
    header('Location: index.php?view=admin/management/login');
    exit;
}

// Crear usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create_user') {
    require_once(__DIR__ . '/app/Models/UserModel.php');
    $model = new UserModel();

    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $existingUser = $model->findByUsername($username);

    if ($existingUser) {
        header("Location: index.php?view=admin/management/create_user&msg=" . urlencode("⚠️ El usuario '$username' ya existe."));
        exit;
    }

    $model->createUser($username, $password, $role);
    header("Location: index.php?view=admin/management/users&msg=" . urlencode("✅ Usuario creado correctamente."));
    exit;
}


// ✅ Acción: deshabilitar usuario (GET)
if ($action === 'disable' && isset($_GET['user_id'])) {
    require_once(__DIR__ . '/app/Models/UserModel.php');
    $model = new UserModel();
    $model->disableUser($_GET['user_id']);
    header("Location: index.php?view=admin/management/users");
    exit;
}

// ✅ Acción: habilitar usuario (GET)
if ($action === 'enable' && isset($_GET['user_id'])) {
    require_once(__DIR__ . '/app/Models/UserModel.php');
    $model = new UserModel();
    $model->enableUser($_GET['user_id']);
    header("Location: index.php?view=admin/management/users");
    exit;
}

// ✅ Actualizar usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update_user') {
    require_once(__DIR__ . '/app/Models/UserModel.php');
    $model = new UserModel();
    $model->updateUser($_POST['user_id'], $_POST['username'], $_POST['role']);
    header("Location: index.php?view=admin/management/users&msg=Usuario actualizado");
    exit;
}

if ($action === 'delete_event' && isset($_GET['event_id'])) {
    require_once(__DIR__ . '/app/Controllers/EventController.php');
    (new EventController())->deleteEvent();
    exit;
}


// ✅ Mostrar la vista correspondiente (último paso)
if (array_key_exists($view, $rutas_validas)) {
    require_once(__DIR__ . '/' . $rutas_validas[$view]);
} else {
    echo "<h2>❌ Vista no encontrada</h2>";
}

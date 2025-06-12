<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php?view=admin/management/login");
    exit();
}

$eventId = $_GET['event_id'] ?? null;
$model = new LogModel();

$search = $_GET['search'] ?? '';
$date = $_GET['date'] ?? '';
$page = $_GET['page'] ?? 1;
$limit = 20;
$offset = ($page - 1) * $limit;

$total = $model->countLogsByEvent($eventId, $search, $date);
$logs = $model->getLogsByEvent($eventId, $search, $date, $limit, $offset);
$totalPages = ceil($total / $limit);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Logs del Evento</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/qsl_virtual/app/Views/styles/list_logs.css" rel="stylesheet">

    <script>
        function confirmLogout() {
            if (confirm("¿Estás seguro de que deseas cerrar sesión?")) {
                window.location.href = "/qsl_virtual/index.php?action=logout";
            }
        }
    </script>
    <script>
        function toggleLogForm() {
            const form = document.getElementById('logForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>

</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark bg-opacity-90 px-4">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <span class="navbar-text text-white fw-bold">QSL virtual Ecuador</span>
            <a href="#" onclick="confirmLogout()" class="btn btn-danger">
                <i class="bi bi-box-arrow-right"></i> Cerrar sesión
            </a>
        </div>
    </nav>

    <div class="container py-5">
        <h2 class="text-black mb-4 text-center">Registros del evento</h2>

        <!-- Filtros -->
        <form method="GET" class="d-flex flex-wrap gap-2 mb-4 justify-content-center">
            <input type="hidden" name="view" value="admin/logs/list_logs">
            <input type="hidden" name="event_id" value="<?= $eventId ?>">
            <input type="text" name="search" class="form-control" placeholder="Buscar..." value="<?= htmlspecialchars($search) ?>">
            <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($date) ?>">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel-fill"></i> Filtrar
            </button>
        </form>

        <!-- Tabla de logs -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover bg-white text-center align-middle shadow-sm">
                <thead class="table-dark text-white">
                    <tr>
                        <th>Fecha</th>
                        <th>Hora UTC</th>
                        <th>Call</th>
                        <th>Band</th>
                        <th>Modo</th>
                        <th>RST Sent</th>
                        <th>RST Rcvd</th>
                        <th>Freq</th>
                        <th>Estación</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?= $log['date_log'] ?></td>
                            <td><?= $log['utc_log'] ?></td>
                            <td><?= $log['call_log'] ?></td>
                            <td><?= $log['band_log'] ?></td>
                            <td><?= $log['mode_log'] ?></td>
                            <td><?= $log['rst_sent_log'] ?></td>
                            <td><?= $log['rst_rcvd_log'] ?></td>
                            <td><?= $log['freq_log'] ?></td>
                            <td><?= $log['station_callsign_log'] ?></td>
                            <td>
                                <?= $log['status_log'] ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>' ?>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <a href="/qsl_virtual/index.php?view=admin/logs/edit_log&log_id=<?= $log['log_id'] ?>" class="btn btn-warning btn-sm" title="Editar"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="/qsl_virtual/index.php?action=toggle_log_status&log_id=<?= $log['log_id'] ?>&event_id=<?= $eventId ?>" class="btn btn-secondary btn-sm" title="Activar/Inactivar"><i class="bi bi-power"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <nav class="d-flex justify-content-center mt-4">
            <ul class="pagination pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?view=admin/logs/list_logs&event_id=<?= $eventId ?>&page=<?= $i ?>&search=<?= urlencode($search) ?>&date=<?= urlencode($date) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
        <div class="text-center mt-5 mb-3">
            <button class="btn btn-success btn" onclick="toggleLogForm()">
                <i class="bi bi-plus-circle-fill"></i> Insertar Log Manual
            </button>
        </div>



        <!-- Insertar log manual -->
        <div id="logForm" style="display: none;">

            <h3 class="text-black mt-5 mb-3 text-center">Insertar Log Manual</h3>
            <form method="POST" action="/qsl_virtual/index.php?action=insert_manual_log" class="bg-light p-4 rounded shadow mx-auto" style="max-width: 800px;">
                <input type="hidden" name="event_id" value="<?= $eventId ?>">

                <div class="row g-3">
                    <div class="col-md-3"><input type="text" name="call_log" class="form-control" placeholder="Call" required></div>
                    <div class="col-md-3"><input type="date" name="date_log" class="form-control" required></div>
                    <div class="col-md-3"><input type="time" name="utc_log" class="form-control" required></div>
                    <div class="col-md-3"><input type="text" name="band_log" class="form-control" placeholder="Band"></div>
                    <div class="col-md-3"><input type="text" name="mode_log" class="form-control" placeholder="Mode"></div>
                    <div class="col-md-3"><input type="text" name="rst_sent_log" class="form-control" placeholder="RST Sent"></div>
                    <div class="col-md-3"><input type="text" name="rst_rcvd_log" class="form-control" placeholder="RST Rcvd"></div>
                    <div class="col-md-3"><input type="text" name="freq_log" class="form-control" placeholder="Freq"></div>
                    <div class="col-md-4"><input type="text" name="station_callsign_log" class="form-control" placeholder="Estación"></div>
                    <div class="col-md-4"><input type="text" name="my_gridsquare_log" class="form-control" placeholder="My Grid"></div>
                    <div class="col-md-4"><input type="text" name="comment_log" class="form-control" placeholder="Comentario"></div>
                </div>

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary btn"><i class="bi bi-plus-circle-fill"></i> Insertar</button>
                </div>
            </form>
        </div>


        <!-- Botón volver -->
        <div class="text-center mt-5">
            <a href="/qsl_virtual/index.php?view=admin/events/list_events" class="btn btn-secondary btn">
                ← Volver a la lista de eventos
            </a>
        </div>
    </div>
</body>
</html>
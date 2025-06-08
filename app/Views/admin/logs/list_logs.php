<?php

$eventId = $_GET['event_id'] ?? null;
$model = new LogModel();

// Filtros
$search = $_GET['search'] ?? '';
$date = $_GET['date'] ?? '';

// Paginación
$page = $_GET['page'] ?? 1;
$limit = 20;
$offset = ($page - 1) * $limit;

$total = $model->countLogsByEvent($eventId, $search, $date);
$logs = $model->getLogsByEvent($eventId, $search, $date, $limit, $offset);

$totalPages = ceil($total / $limit);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Logs del Evento</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        form {
            margin-bottom: 20px;
        }

        .acciones a {
            margin: 0 5px;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <h2>Logs del Evento <?= $eventId ?></h2>

    <!-- Filtro -->
    <form method="GET">
        <input type="hidden" name="view" value="admin/logs/list_logs">
        <input type="hidden" name="event_id" value="<?= $eventId ?>">
        <input type="text" name="search" placeholder="Buscar..." value="<?= htmlspecialchars($search) ?>">
        <input type="date" name="date" value="<?= htmlspecialchars($date) ?>">
        <button type="submit">Filtrar</button>
    </form>

    <table>
        <thead>
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
                    <td><?= $log['status_log'] ? 'Activo' : 'Inactivo' ?></td>
                    <td class="acciones">
                        <a href="/qsl_virtual/public/index.php?view=admin/logs/edit_log&log_id=<?= $log['log_id'] ?>">✏️Editar</a>
                        <a href="/qsl_virtual/public/index.php?action=toggle_log_status&log_id=<?= $log['log_id'] ?>&event_id=<?= $eventId ?>">🚫Activar o inactivar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <div style="margin-top: 15px;">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?view=admin/logs/list_logs&event_id=<?= $eventId ?>&page=<?= $i ?>&search=<?= urlencode($search) ?>&date=<?= urlencode($date) ?>" style="<?= $i == $page ? 'font-weight:bold' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>

    <!-- Formulario manual -->
    <h3>Insertar Log Manual</h3>
    <form method="POST" action="/qsl_virtual/public/index.php?action=insert_manual_log">
        <input type="hidden" name="event_id" value="<?= $eventId ?>">
        <input type="text" name="call_log" placeholder="Call" required>
        <input type="date" name="date_log" required>
        <input type="time" name="utc_log" required>
        <input type="text" name="band_log" placeholder="Band">
        <input type="text" name="mode_log" placeholder="Mode">
        <input type="text" name="rst_sent_log" placeholder="RST Sent">
        <input type="text" name="rst_rcvd_log" placeholder="RST Rcvd">
        <input type="text" name="freq_log" placeholder="Freq">
        <input type="text" name="station_callsign_log" placeholder="Estación">
        <input type="text" name="my_gridsquare_log" placeholder="My Grid">
        <input type="text" name="comment_log" placeholder="Comentario">
        <button type="submit">Insertar</button>
    </form>
    <a href="/qsl_virtual/public/index.php?view=admin/events/list_events">← Volver a la lista de eventos</a>

</body>

</html>
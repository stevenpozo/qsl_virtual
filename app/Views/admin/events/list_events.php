<?php
$model = new EventModel();

// Filtros y paginación
$search = $_GET['search'] ?? '';
$date_filter = $_GET['date'] ?? '';
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

$total = $model->countFilteredEvents($search, $date_filter);
$eventos = $model->getFilteredEvents($search, $date_filter, $per_page, $offset);
$total_pages = ceil($total / $per_page);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Lista de Eventos</title>
    <style>
        table {
            width: 90%;
            border-collapse: collapse;
            margin: 20px auto;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        img {
            max-width: 150px;
            height: auto;
        }

        h2 {
            text-align: center;
        }

        .volver,
        .paginacion,
        .filtros {
            text-align: center;
            margin: 20px auto;
        }

        .volver a,
        .paginacion a {
            text-decoration: none;
            padding: 8px 16px;
            background: #007bff;
            color: white;
            border-radius: 5px;
            margin: 0 5px;
        }

        .filtros input[type="text"],
        .filtros input[type="date"] {
            padding: 6px;
            margin: 0 5px;
        }

        .filtros button {
            padding: 6px 12px;
        }
    </style>
</head>

<?php if (isset($_GET['msg'])): ?>
    <div style="background: #d1ffd1; border: 1px solid green; padding: 10px; width: 80%; margin: 20px auto; text-align: center;">
        <?= htmlspecialchars($_GET['msg']) ?>
    </div>
<?php endif; ?>

<body>
    <div style="text-align: right; margin: 20px; padding-right: 30px;">
        <a href="/qsl_virtual/public/index.php?view=admin/management/dashboard" style="text-decoration: none; padding: 8px 16px; background: #28a745; color: white; border-radius: 5px;">
            ⬅ Volver al Dashboard
        </a>
    </div>


    <h2>Eventos Registrados</h2>

    <div class="filtros">
        <form method="get" action="">
            <input type="hidden" name="view" value="admin/events/list_events">
            <input type="text" name="search" placeholder="Buscar por palabra..." value="<?= htmlspecialchars($search) ?>">
            <input type="date" name="date" value="<?= htmlspecialchars($date_filter) ?>">
            <button type="submit">Filtrar</button>
        </form>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Nombre del Evento</th>
            <th>Fecha</th>
            <th>Call</th>
            <th>Imagen</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>

        <?php foreach ($eventos as $evento): ?>
            <tr>
                <td><?= $evento['event_id'] ?></td>
                <td><?= $evento['name_event'] ?></td>
                <td><?= $evento['date_event'] ?></td>
                <td><?= $evento['call_event'] ?></td>
                <td><img src="/qsl_virtual/uploads/<?= $evento['image_event'] ?>" alt="imagen_evento"></td>
                <td>
                    <?php if ($evento['status_event'] == 1): ?>
                        <span style="color: green; font-weight: bold;">Activo</span>
                    <?php else: ?>
                        <span style="color: red; font-weight: bold;">Inactivo</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="/qsl_virtual/public/index.php?view=admin/events/edit_event&event_id=<?= $evento['event_id'] ?>">Edit</a><br>
                    <a href="/qsl_virtual/public/index.php?view=admin/logs/list_logs&event_id=<?= $evento['event_id'] ?>">View Logs</a><br>
                    <a href="/qsl_virtual/public/index.php?view=admin/logs/load_log&event_id=<?= $evento['event_id'] ?>">Upload Logs (.ADI)</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div class="paginacion">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?view=admin/events/list_events&search=<?= urlencode($search) ?>&date=<?= urlencode($date_filter) ?>&page=<?= $i ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>

    <div class="volver">
        <a href="/qsl_virtual/public/index.php?view=admin/events/crear_evento">Crear Nuevo Evento</a>
    </div>

    <a href="/qsl_virtual/public/index.php?view=admin/events/statistics_events">📊 Ver estadísticas</a>

</body>

</html>
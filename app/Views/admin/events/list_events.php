<?php
require_once(__DIR__ . '/../../../../config/constants.php');
$model = new EventModel();

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
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Eventos Registrados</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- CSS personalizado -->
    <link href="<?= BASE_URL ?>/styles/list_events.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</head>

<body>
    <!-- Navbar -->
    <?php include(APP_PATH . 'app/Include/navbar.php'); ?>


    <div class="container py-5">
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success text-center"><?= htmlspecialchars($_GET['msg']) ?></div>
        <?php endif; ?>

        <!-- Filtros y botón crear evento -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <form method="get" class="d-flex flex-wrap gap-2">
                <input type="hidden" name="view" value="admin/events/list_events">
                <input type="text" name="search" class="form-control" placeholder="Buscar por palabra..." value="<?= htmlspecialchars($search) ?>">
                <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($date_filter) ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel-fill"></i> Filtrar
                </button>
            </form>
            <a href="<?= BASE_URL ?>/index.php?view=admin/events/crear_evento" class="btn btn-success">
                <i class="bi bi-plus-circle-fill"></i> Crear nuevo evento
            </a>
        </div>

        <!-- Tabla de eventos -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center bg-white rounded-3 overflow-hidden shadow">
                <thead class="table-dark text-white">
                    <tr>
                        <th>Nombre del Evento</th>
                        <th>Fecha</th>
                        <th>Call</th>
                        <th>Imagen</th>
                        <th>🎨 Color</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eventos as $evento): ?>
                        <tr>
                            <td><?= $evento['name_event'] ?></td>
                            <td><?= $evento['date_event'] ?></td>
                            <td><?= $evento['call_event'] ?></td>
                            <td><img src="<?= BASE_URL ?>/uploads/<?= $evento['image_event'] ?>" class="img-thumbnail" width="120">
                            </td>
                            <td>
                                <div style="width: 40px; height: 25px; margin: auto; border: 1px solid #000; background-color: <?= htmlspecialchars($evento['color_event']) ?>"></div>
                            </td>
                            <td>
                                <?php if ($evento['status_event'] == 1): ?>
                                    <span class="badge bg-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-2 justify-content-center">
                                    <a href="index.php?view=admin/events/edit_event&event_id=<?= $evento['event_id'] ?>"
                                        class="btn btn-warning btn-sm" title="Editar evento">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>

                                    <a href="index.php?view=admin/logs/list_logs&event_id=<?= $evento['event_id'] ?>"
                                        class="btn btn-secondary btn-sm" title="Ver logs">
                                        <i class="bi bi-file-earmark-text-fill"></i>
                                    </a>

                                    <a href="index.php?view=admin/logs/load_log&event_id=<?= $evento['event_id'] ?>"
                                        class="btn btn-primary btn-sm" title="Subir logs .ADI">
                                        <i class="bi bi-upload"></i>
                                    </a>

                                    <a href="<?= BASE_URL ?>/index.php?action=delete_event&event_id=<?= $evento['event_id'] ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Estás seguro de que deseas eliminar este evento y todos sus logs?');"
                                        title="Eliminar evento">
                                        <i class="bi bi-trash3-fill"></i>
                                    </a>

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
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?view=admin/events/list_events&search=<?= urlencode($search) ?>&date=<?= urlencode($date_filter) ?>&page=<?= $i ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>

        <!-- Botón regresar -->
        <div class="text-center mt-4">
            <a href="<?= BASE_URL ?>/index.php?view=admin/management/dashboard" class="btn btn-secondary btn">
                ⬅ Volver al Dashboard
            </a>
            <a href="<?= BASE_URL ?>/index.php?view=admin/statistics/statistics_events"
                class="btn btn-outline-primary btn ms-2">
                📊 Ver estadísticas
            </a>
        </div>
    </div>
</body>

</html>
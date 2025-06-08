<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php?view=admin/management/login");
    exit();
}

$model = new EventModel();
$eventId = $_GET['event_id'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cargar archivo ADI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/qsl_virtual/app/Views/styles/load_log.css" rel="stylesheet">

    <script>
        function confirmLogout() {
            if (confirm("¿Estás seguro de que deseas cerrar sesión?")) {
                window.location.href = "/qsl_virtual/public/index.php?action=logout";
            }
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

    <!-- Contenido -->
    <div class="container py-5">
        <div class="form-box mx-auto">
            <h2 class="text-center mb-4">Cargar archivo .ADI</h2>

            <form action="/qsl_virtual/app/Controllers/LogController.php" method="post" enctype="multipart/form-data">
                <?php if ($eventId): ?>
                    <?php $evento = $model->getEventById($eventId); ?>
                    <p><strong>Evento seleccionado:</strong> <?= $evento['name_event'] ?> (<?= $evento['date_event'] ?>)</p>
                    <input type="hidden" name="event_id" value="<?= $evento['event_id'] ?>">
                <?php else: ?>
                    <div class="mb-3">
                        <label class="form-label">Seleccionar Evento:</label>
                        <select name="event_id" class="form-select" required>
                            <?php foreach ($model->getAllEvents() as $evento): ?>
                                <option value="<?= $evento['event_id'] ?>">
                                    <?= $evento['name_event'] ?> (<?= $evento['date_event'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">Archivo .ADI:</label>
                    <input type="file" name="adi_file" class="form-control" accept=".adi" required>
                </div>

                <button type="submit" class="btn btn-success w-100">
                    <i class="bi bi-upload"></i> Procesar Logs
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="/qsl_virtual/public/index.php?view=admin/events/list_events" class="btn btn-secondary">
                    ← Volver a la lista de eventos
                </a>
            </div>
        </div>
    </div>
</body>
</html>

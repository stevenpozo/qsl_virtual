<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php?view=admin/management/login");
    exit();
}

if (!isset($_GET['event_id'])) {
    echo "ID del evento no proporcionado.";
    exit;
}

$model = new EventModel();
$evento = $model->getEventById($_GET['event_id']);

if (!$evento) {
    echo "Evento no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Evento</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Estilo personalizado -->
    <link href="/qsl_virtual/app/Views/styles/edit_event.css" rel="stylesheet">

    <script>
        function confirmLogout() {
            if (confirm("¿Estás seguro de que deseas cerrar sesión?")) {
                window.location.href = "/qsl_virtual/index.php?action=logout";
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

    <!-- Formulario principal -->
    <div class="container py-5">
        <div class="form-box mx-auto">
            <h2 class="text-center mb-4">EDITAR EVENTO</h2>

            <form action="/qsl_virtual/index.php?view=admin/events/edit_event" method="post" enctype="multipart/form-data">
                <input type="hidden" name="event_id" value="<?= $evento['event_id'] ?>">

                <div class="mb-3">
                    <label class="form-label">Nombre del Evento:</label>
                    <input type="text" name="name_event" class="form-control" value="<?= $evento['name_event'] ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fecha del Evento:</label>
                    <input type="date" name="date_event" class="form-control" value="<?= $evento['date_event'] ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Call Event:</label>
                    <input type="text" name="call_event" class="form-control" value="<?= $evento['call_event'] ?>" required>
                </div>

                <div class="mb-4 row align-items-center">
                    <div class="col-md-5 text-center mb-3 mb-md-0">
                        <label class="form-label">Imagen actual:</label><br>
                        <img src="/qsl_virtual/uploads/<?= $evento['image_event'] ?>" class="img-thumbnail" width="150">
                    </div>
                    <div class="col-md-7">
                        <label class="form-label">Cambiar imagen (opcional):</label>
                        <input type="file" name="image_event" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Color del texto en diploma:</label>
                    <input type="color" name="color_event" class="form-control form-control-color" value="<?= htmlspecialchars($evento['color_event']) ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Estado:</label>
                    <select name="status_event" class="form-select">
                        <option value="1" <?= $evento['status_event'] == 1 ? 'selected' : '' ?>>Activo</option>
                        <option value="0" <?= $evento['status_event'] == 0 ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>

                <button type="submit" name="edit_event" class="btn btn-primary w-100 btn">
                    <i class="bi bi-save-fill"></i> Guardar Cambios
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="/qsl_virtual/index.php?view=admin/events/list_events" class="btn btn-secondary">
                    ← Volver a la lista de eventos
                </a>
            </div>
        </div>
    </div>
</body>
</html>

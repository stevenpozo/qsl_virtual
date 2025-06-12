<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php?view=admin/management/login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Evento</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Estilos personalizados -->
    <link href="/qsl_virtual/app/Views/styles/crear_evento.css" rel="stylesheet">

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

    <!-- Contenido principal -->
    <div class="container py-5">
        <div class="form-box mx-auto">
            <h2 class="text-center mb-4">CREAR EVENTO</h2>

            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-danger text-center"><?= htmlspecialchars($_GET['msg']) ?></div>
            <?php endif; ?>

            <form action="/qsl_virtual/index.php?action=create_event" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Nombre del Evento:</label>
                    <input type="text" name="name_event" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fecha del Evento:</label>
                    <input type="date" name="date_event" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Call Event:</label>
                    <input type="text" name="call_event" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Imagen (JPG):</label>
                    <input type="file" name="image_event" accept="image/jpeg" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Color del texto en diploma:</label>
                    <input type="color" name="color_event" class="form-control form-control-color" value="#000000" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 btn">
                    <i class="bi bi-save-fill"></i> Guardar Evento
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="/qsl_virtual/index.php?view=admin/events/list_events" class="btn btn-secondary">
                    ⬅ Volver a lista de eventos
                </a>
            </div>
        </div>
    </div>
</body>
</html>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    header('Location: index.php?view=admin/management/login');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Estilo personalizado -->
    <link href="/qsl_virtual/app/Views/styles/dashboard.css" rel="stylesheet">
</head>
<body>

    <!-- Navbar con Logout -->
    <nav class="navbar navbar-dark bg-dark bg-opacity-90 px-4">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <span class="navbar-text text-white fw-bold">QSL virtual Ecuador</span>
            <a href="#" onclick="confirmLogout()" class="btn btn-danger">
                <i class="bi bi-box-arrow-right"></i> Cerrar sesión
            </a>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container py-5 text-center">
        <h2 class="text-black mb-5">Bienvenido  <?= htmlspecialchars($_SESSION['username']) ?></h2>

        <div class="d-flex justify-content-center flex-wrap gap-4">
            <a href="index.php?view=admin/events/list_events" class="dashboard-card text-decoration-none">
                <i class="bi bi-calendar-event-fill"></i>
                <span>Eventos</span>
            </a>

            <a href="index.php?view=admin/management/users" class="dashboard-card text-decoration-none">
                <i class="bi bi-people-fill"></i>
                <span>Gestión de usuarios</span>
            </a>
        </div>
    </div>

    <script>
        function confirmLogout() {
            if (confirm("¿Estás seguro de que deseas cerrar sesión?")) {
                window.location.href = "/qsl_virtual/public/index.php?action=logout";
            }
        }
    </script>

</body>
</html>

<?php if (!isset($events)): ?>
    <p>Error: no se cargaron los eventos.</p>
<?php exit;
endif; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Estadísticas por Evento</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="/qsl_virtual/app/Views/styles/statistics_events.css">
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

    <div class="container py-5">
        <h2 class="text-center mb-4">Estadísticas de Eventos</h2>

        <!-- Tabla de estadísticas -->
        <div class="table-responsive mb-5">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Evento</th>
                        <th>Fecha</th>
                        <th>Call</th>
                        <th>Total de Logs</th>
                        <th>Contactos Únicos</th>
                        <th>Última Fecha</th>
                        <th>Banda más usada</th>
                        <th>Modo más usado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): $stats = $event['stats']; ?>
                        <tr>
                            <td><?= $event['event_id'] ?></td>
                            <td><?= htmlspecialchars($event['name_event']) ?></td>
                            <td><?= $event['date_event'] ?></td>
                            <td><?= $event['call_event'] ?></td>
                            <td><?= $stats['total_logs'] ?? 0 ?></td>
                            <td><?= $stats['unique_calls'] ?? 0 ?></td>
                            <td><?= $stats['last_date'] ?? 'N/A' ?></td>
                            <td><?= $stats['top_band'] ?? 'N/A' ?></td>
                            <td><?= $stats['top_mode'] ?? 'N/A' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Botón volver -->
        <div class="text-center mt-4">
            <a href="/qsl_virtual/index.php?view=admin/events/list_events" class="btn btn-secondary">
                ← Volver a Eventos
            </a>
        </div>
    </div>

</body>

</html>
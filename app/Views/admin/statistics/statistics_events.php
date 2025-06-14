<?php
require_once(__DIR__ . '/../../../../config/constants.php');

if (!isset($events)): ?>
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
    <link rel="stylesheet" href="<?= BASE_URL ?>/styles/statistics_events.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</head>

<body>
    <!-- Navbar -->
    <?php include(APP_PATH . 'app/Include/navbar.php'); ?>


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
            <a href="<?= BASE_URL ?>/index.php?view=admin/events/list_events" class="btn btn-secondary">
                ← Volver a Eventos
            </a>
        </div>
    </div>

</body>

</html>
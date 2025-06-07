<?php if (!isset($events)): ?>
    <p>Error: no se cargaron los eventos.</p>
<?php exit; endif; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Estadísticas por Evento</title>
    <style>
        /* (estilos como antes) */
    </style>
</head>
<body>
    <h2>Estadísticas de Eventos</h2>
    <table>
        <thead>
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

    <div class="volver">
        <a href="/qsl_virtual/public/index.php?view=admin/events/list_events">Volver a Eventos</a>
    </div>
</body>
</html>

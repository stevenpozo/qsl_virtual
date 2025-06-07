<?php
$model = new EventModel();
$eventos = $model->obtenerEventos();
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

        th, td {
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

        .volver {
            text-align: center;
            margin-top: 20px;
        }

        .volver a {
            text-decoration: none;
            padding: 8px 16px;
            background: #007bff;
            color: white;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <h2>Eventos Registrados</h2>

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
                <a href="/qsl_virtual/public/index.php?view=admin/events/edit_event&event_id=<?= $evento['event_id'] ?>">Editar Evento</a><br>
                <a href="/qsl_virtual/public/index.php?view=admin/logs/list_logs&event_id=<?= $evento['event_id'] ?>">Ver Logs</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="volver">
        <a href="/qsl_virtual/public/index.php?view=admin/events/crear_evento">Crear Nuevo Evento</a>
    </div>
</body>
</html>

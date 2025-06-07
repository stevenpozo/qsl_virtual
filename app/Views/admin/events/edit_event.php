<?php

if (!isset($_GET['event_id'])) {
    echo "ID del evento no proporcionado.";
    exit;
}

$model = new EventModel();
$evento = $model->obtenerEventoPorId($_GET['event_id']);

if (!$evento) {
    echo "Evento no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Editar Evento</title>
</head>

<body>
    <h2>Editar Evento</h2>

    <form action="/qsl_virtual/public/index.php?view=admin/events/edit_event" method="post" enctype="multipart/form-data">
        <input type="hidden" name="event_id" value="<?= $evento['event_id'] ?>">

        <label>Nombre del Evento:</label><br>
        <input type="text" name="name_event" value="<?= $evento['name_event'] ?>" required><br><br>

        <label>Fecha del Evento:</label><br>
        <input type="date" name="date_event" value="<?= $evento['date_event'] ?>" required><br><br>

        <label>Call Event:</label><br>
        <input type="text" name="call_event" value="<?= $evento['call_event'] ?>" required><br><br>

        <label>Imagen actual:</label><br>
        <img src="/qsl_virtual/uploads/<?= $evento['image_event'] ?>" width="150">

        <label>Cambiar imagen (opcional):</label><br>
        <input type="file" name="image_event"><br><br>

        <label>Estado:</label><br>
        <select name="status_event">
            <option value="1" <?= $evento['status_event'] == 1 ? 'selected' : '' ?>>Activo</option>
            <option value="0" <?= $evento['status_event'] == 0 ? 'selected' : '' ?>>Inactivo</option>
        </select><br><br>

        <input type="submit" name="edit_event" value="Guardar Cambios">
    </form>

    <br>
    <a href="/qsl_virtual/public/index.php?view=admin/events/list_events">← Volver a la lista de eventos</a>
</body>

</html>
<!DOCTYPE html>
<html>

<head>
    <title>Crear Evento</title>
</head>

<body>
    <h2>Crear Nuevo Evento</h2>
    <form action="/qsl_virtual/public/index.php?action=create_event" method="post" enctype="multipart/form-data">
        <label>Nombre del Evento:</label><br>
        <input type="text" name="name_event" required><br><br>

        <label>Fecha del Evento:</label><br>
        <input type="date" name="date_event" required><br><br>

        <label>Call Event:</label><br>
        <input type="text" name="call_event" required><br><br>

        <label>Imagen (JPG):</label><br>
        <input type="file" name="image_event" accept="image/jpeg" required><br><br>

        <input type="submit" value="Guardar Evento">
    </form>
</body>

<?php if (isset($_GET['msg'])): ?>
    <div style="background: #ffd1d1; color: red; padding: 10px; border: 1px solid red;">
        <?= htmlspecialchars($_GET['msg']) ?>
    </div>
<?php endif; ?>


</html>
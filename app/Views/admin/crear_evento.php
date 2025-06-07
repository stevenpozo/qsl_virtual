<!DOCTYPE html>
<html>
<head>
    <title>Crear Evento</title>
</head>
<body>
    <h2>Crear Nuevo Evento</h2>
    <form action="../../Controllers/EventoController.php" method="post" enctype="multipart/form-data">
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
</html>

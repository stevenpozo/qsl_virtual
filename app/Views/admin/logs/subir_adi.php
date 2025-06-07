<!DOCTYPE html>
<html>
<head>
    <title>Subir Archivo ADI</title>
</head>
<body>
    <h2>Subir Logs desde archivo .adi</h2>
    <form action="../../Controllers/LogController.php" method="post" enctype="multipart/form-data">
        <label>Selecciona el evento:</label><br>
        <select name="event_id" required>
            <?php
            require_once("../../Models/EventModel.php");
            $model = new EventModel();
            $eventos = $model->obtenerEventos();
            foreach ($eventos as $evento) {
                echo "<option value='{$evento['event_id']}'>{$evento['name_event']} ({$evento['date_event']})</option>";
            }
            ?>
        </select><br><br>

        <label>Archivo .adi:</label>
        <input type="file" name="adi_file" accept=".adi" required><br><br>

        <input type="submit" value="Procesar Logs">
    </form>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Upload ADI File</title>
</head>
<body>
    <h2>Upload Logs from .adi File</h2>

    <form action="/qsl_virtual/app/Controllers/LogController.php" method="post" enctype="multipart/form-data">
        <?php
        $model = new EventModel();
        $eventId = $_GET['event_id'] ?? null;

        if ($eventId) {
            // Si viene por la URL, ocultar el campo y mostrar el nombre
            $evento = $model->getEventById($eventId);
            echo "<p><strong>Event:</strong> {$evento['name_event']} ({$evento['date_event']})</p>";
            echo "<input type='hidden' name='event_id' value='{$evento['event_id']}'>";
        } else {
            // Si no viene por URL, mostrar select
            echo "<label>Select Event:</label><br>";
            echo "<select name='event_id' required>";
            $eventos = $model->getAllEvents();
            foreach ($eventos as $evento) {
                echo "<option value='{$evento['event_id']}'>{$evento['name_event']} ({$evento['date_event']})</option>";
            }
            echo "</select><br><br>";
        }
        ?>

        <label>ADI File:</label>
        <input type="file" name="adi_file" accept=".adi" required><br><br>

        <input type="submit" value="Process Logs">
    </form>

    <br>
    <a href="/qsl_virtual/public/index.php?view=admin/events/list_events">← Back to Event List</a>
</body>
</html>

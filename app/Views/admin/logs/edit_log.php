<?php
// Ya no necesitas require_once aquí
$log = $model->getLogById($_GET['log_id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Log</title>
    <style>
        form {
            width: 90%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        input, button {
            display: block;
            width: 100%;
            margin: 10px 0;
            padding: 10px;
        }

        .volver {
            text-align: center;
            margin-top: 20px;
        }

        .volver a {
            text-decoration: none;
            background: #007bff;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">Editar Log</h2>

<form method="POST" action="/qsl_virtual/public/index.php?action=update_log">
    <input type="hidden" name="log_id" value="<?= $log['log_id'] ?>">
    <input type="hidden" name="event_id" value="<?= $log['event_id'] ?>">

    <input type="text" name="call_log" value="<?= $log['call_log'] ?>" required>
    <input type="date" name="date_log" value="<?= $log['date_log'] ?>" required>
    <input type="time" name="utc_log" value="<?= $log['utc_log'] ?>" required>
    <input type="text" name="time_off_log" value="<?= $log['time_off_log'] ?>">
    <input type="text" name="band_log" value="<?= $log['band_log'] ?>">
    <input type="text" name="mode_log" value="<?= $log['mode_log'] ?>">
    <input type="text" name="rst_sent_log" value="<?= $log['rst_sent_log'] ?>">
    <input type="text" name="rst_rcvd_log" value="<?= $log['rst_rcvd_log'] ?>">
    <input type="text" name="freq_log" value="<?= $log['freq_log'] ?>">
    <input type="text" name="station_callsign_log" value="<?= $log['station_callsign_log'] ?>">
    <input type="text" name="my_gridsquare_log" value="<?= $log['my_gridsquare_log'] ?>">
    <input type="text" name="comment_log" value="<?= $log['comment_log'] ?>">

    <button type="submit">Actualizar</button>
</form>

<div class="volver">
    <a href="/qsl_virtual/public/index.php?view=admin/logs/list_logs&event_id=<?= $log['event_id'] ?>">← Volver a la lista de logs</a>
</div>

</body>
</html>

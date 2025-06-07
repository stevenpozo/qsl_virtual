<?php
require_once(__DIR__ . '/../../../app/Models/LogModel.php');
$model = new LogModel();

$log = $model->getLogById($_GET['log_id']);
?>

<h2>Editar Log</h2>
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

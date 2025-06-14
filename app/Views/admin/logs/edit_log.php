<?php
require_once(__DIR__ . '/../../../../config/constants.php');
require_once(__DIR__ . '/../../../Models/LogModel.php');

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php?view=admin/management/login");
    exit();
}

$model = new LogModel();
$log = $model->getLogById($_GET['log_id'] ?? 0);
if (!$log) {
    echo "Log no encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Log</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/styles/edit_log.css" rel="stylesheet">

    <script>
        function confirmUpdate() {
            return confirm("¿Deseas guardar los cambios en este log?");
        }
    </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</head>

<body>
    <!-- Navbar -->
        <?php include(APP_PATH . 'app/Include/navbar.php'); ?>


    <!-- Formulario -->
    <div class="container py-5">
        <div class="form-box mx-auto">

            <form method="POST" action="<?= BASE_URL ?>/index.php?action=update_log" onsubmit="return confirmUpdate()">
                <h2 class="text-center text-black mb-4">EDITAR LOG</h2>

                <input type="hidden" name="log_id" value="<?= $log['log_id'] ?>">
                <input type="hidden" name="event_id" value="<?= $log['event_id'] ?>">

                <div class="mb-3">
                    <label class="form-label">Callsign</label>
                    <input type="text" name="call_log" class="form-control" value="<?= $log['call_log'] ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="date_log" class="form-control" value="<?= $log['date_log'] ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Hora UTC</label>
                    <input type="time" name="utc_log" class="form-control" value="<?= $log['utc_log'] ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Hora OFF (opcional)</label>
                    <input type="text" name="time_off_log" class="form-control" value="<?= $log['time_off_log'] ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Banda</label>
                    <input type="text" name="band_log" class="form-control" value="<?= $log['band_log'] ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Modo</label>
                    <input type="text" name="mode_log" class="form-control" value="<?= $log['mode_log'] ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">RST Enviado</label>
                    <input type="text" name="rst_sent_log" class="form-control" value="<?= $log['rst_sent_log'] ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">RST Recibido</label>
                    <input type="text" name="rst_rcvd_log" class="form-control" value="<?= $log['rst_rcvd_log'] ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Frecuencia</label>
                    <input type="text" name="freq_log" class="form-control" value="<?= $log['freq_log'] ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Estación</label>
                    <input type="text" name="station_callsign_log" class="form-control" value="<?= $log['station_callsign_log'] ?>">
                </div>

                <button type="submit" class="btn btn-primary w-100 btn">
                    <i class="bi bi-save-fill"></i> Guardar Cambios
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="<?= BASE_URL ?>/index.php?view=admin/logs/list_logs&event_id=<?= $log['event_id'] ?>" class="btn btn-secondary">
                    ← Volver a la lista de logs
                </a>
            </div>
        </div>
    </div>
</body>

</html>

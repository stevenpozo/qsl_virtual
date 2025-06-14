<?php
require_once(__DIR__ . '/../../../config/constants.php');
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['qsls']) || !isset($_SESSION['call']) || !isset($_SESSION['event_id'])) {
    echo "No hay resultados para mostrar.";
    exit;
}



$logs = $_SESSION['qsls'];
$call = $_SESSION['call'];
$event_id = $_SESSION['event_id'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Resultados de Búsqueda - QSL</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap y estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/styles/list_qsls.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
    <!-- Navbar -->
    <?php include(APP_PATH . 'app/Include/navbar.php'); ?>


    <div class="container py-5">
        <h2 class="text-center text- text-black mb-4">REGISTROS PARA CALL: <strong><?= htmlspecialchars($call) ?></strong></h2>

        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center bg-white rounded-3 overflow-hidden shadow">
                <thead class="table-light">
                    <tr>
                        <th>Confirming QSO with</th>
                        <th>Date</th>
                        <th>UTC</th>
                        <th>Band</th>
                        <th>Mode</th>
                        <th>RST (Sent / Recv)</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?= htmlspecialchars($log['call_log']) ?></td>
                            <td><?= $log['date_log'] ?></td>
                            <td><?= $log['utc_log'] ?></td>
                            <td><?= $log['band_log'] ?></td>
                            <td><?= $log['mode_log'] ?></td>
                            <td><?= $log['rst_rcvd_log'] ?> , <?= $log['rst_sent_log'] ?></td>
                            <td>
                                <form action="<?= BASE_URL ?>/index.php?action=generate_single_qsl_diploma" method="post" target="_blank">
                                    <input type="hidden" name="log_id" value="<?= $log['log_id'] ?>">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-award-fill"></i> Obtener diploma
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <a href="<?= BASE_URL ?>/index.php?view=public/search_qsl" class="btn btn-success btn-lg">
                <i class="bi bi-arrow-left-circle-fill"></i> Volver a buscar
            </a>
        </div>
    </div>
</body>

</html>
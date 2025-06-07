<?php
session_start();
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
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f9f9f9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px auto;
            background: white;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        h2,
        .volver {
            text-align: center;
        }

        .btn {
            padding: 5px 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
        }

        .volver a {
            text-decoration: none;
            padding: 10px 20px;
            background: #28a745;
            color: white;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <h2>Registros encontrados para CALL: <strong><?= htmlspecialchars($call) ?></strong></h2>
    <form action="/qsl_virtual/public/index.php?action=generate_qsl_diploma" method="post" target="_blank">
        <button class="btn" type="submit">🎖️ Obtener diploma QSL</button>
    </form>


    <table>
        <thead>
            <tr>
                <th>Confirming QSO with</th>
                <th>Date</th>
                <th>UTC</th>
                <th>Band</th>
                <th>Mode</th>
                <th>RST (Sent / Recv)</th>
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
                    <td><?= $log['rst_sent_log'] ?> / <?= $log['rst_rcvd_log'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="volver">
        <a href="/qsl_virtual/public/index.php?view=public/search_qsl">🔙 Volver a buscar</a>
    </div>
</body>

</html>
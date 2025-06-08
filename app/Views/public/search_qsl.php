<?php
require_once(__DIR__ . '/../../Models/EventModel.php');
$eventModel = new EventModel();
$events = $eventModel->getAllEvents();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Buscar QSL Virtual</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 30px;
            text-align: center;
        }

        form {
            display: inline-block;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        select,
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 15px 0;
            font-size: 16px;
        }

        button {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .msg {
            margin: 15px auto;
            color: red;
        }
    </style>
</head>

<body>

    <?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
    <div style="text-align: right; margin-bottom: 20px;">
        <?php if (isset($_SESSION['username'])): ?>
            <a href="/qsl_virtual/public/index.php?view=admin/management/dashboard">Dashboard</a> |
            <a href="/qsl_virtual/public/index.php?action=logout">Cerrar sesión</a>
        <?php else: ?>
            <a href="/qsl_virtual/public/index.php?view=admin/management/login">Login</a>
        <?php endif; ?>
    </div>


    <h2>Buscar tarjeta QSL virtual</h2>

    <?php if (isset($_GET['msg'])): ?>
        <div class="msg"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <form action="/qsl_virtual/public/index.php?action=search_logs" method="POST">
        <label for="event_id">Seleccione un evento:</label><br>
        <select name="event_id" required>
            <option value="">-- Seleccione --</option>
            <?php foreach ($events as $event): ?>
                <option value="<?= $event['event_id'] ?>">
                    <?= htmlspecialchars($event['name_event']) ?> (<?= $event['date_event'] ?>)
                </option>
            <?php endforeach; ?>
        </select><br>

        <label for="call">Ingrese su indicativo (CALL):</label><br>
        <input type="text" name="call" placeholder="Ej: PU1AJN" required><br>

        <button type="submit">Buscar registros</button>
    </form>
</body>

</html>
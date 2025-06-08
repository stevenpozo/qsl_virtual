<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    header('Location: index.php?view=admin/management/login');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <style>
        body {
            font-family: Arial;
            background: #f2f2f2;
            padding: 40px;
            text-align: center;
        }

        .box-container {
            display: flex;
            justify-content: center;
            gap: 30px;
        }

        .box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px gray;
            width: 200px;
            cursor: pointer;
            text-decoration: none;
            color: black;
        }

        .box:hover {
            background: #e8f5ff;
        }

        .logout {
            position: absolute;
            top: 20px;
            right: 30px;
        }

        .logout a {
            text-decoration: none;
            background: #dc3545;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            font-weight: bold;
        }

        .logout a:hover {
            background: #c82333;
        }
    </style>
</head>

<body>
    <div class="logout">
        <a href="/qsl_virtual/public/index.php?action=logout">Cerrar sesión</a>
    </div>

    <h2>Bienvenido, <?= htmlspecialchars($_SESSION['username']) ?></h2>
    <div class="box-container">
        <a href="index.php?view=admin/events/list_events" class="box">📅 Eventos</a>
        <a href="index.php?view=admin/management/users" class="box">👥 Gestión de usuarios</a>
    </div>
</body>

</html>

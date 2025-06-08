<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['username'])) {
    header('Location: index.php?view=admin/management/dashboard');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eee;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px gray;
            width: 300px;
            text-align: center;
        }

        input,
        button {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
        }

        .msg {
            color: red;
        }

        .back-btn {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            background: #6c757d;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            font-size: 14px;
        }

        .back-btn:hover {
            background: #5a6268;
        }
    </style>
</head>

<body>
    <div class="login-box">
        <h3>Login</h3>
        <?php if (isset($_GET['error'])): ?>
            <div class="msg"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>
        <form action="index.php?action=login" method="POST">
            <input type="text" name="username" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Entrar</button>
        </form>

    </div>

    <a class="back-btn" href="/qsl_virtual/public/index.php?view=public/search_qsl">← Volver a búsqueda QSL</a>

</body>

</html>
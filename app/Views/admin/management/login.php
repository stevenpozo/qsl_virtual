<?php
require_once(__DIR__ . '/../../../../config/constants.php');

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
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Estilo personalizado -->
    <link href="<?= BASE_URL ?>/styles/login.css" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark bg-opacity-90 px-4">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <span class="navbar-text text-white fw-bold">QSL Virtual Ecuador</span>
        </div>
    </nav>

    <!-- Formulario login -->
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="login-box">
            <h3 class="text-center mb-4">INICIAR SESIÓN</h3>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger p-2 text-center"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>

            <form action="index.php?action=login" method="POST">
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-person-fill"></i> Usuario</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="form-label"><i class="bi bi-lock-fill"></i> Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 btn-lg">
                    <i class="bi bi-box-arrow-in-right"></i> Entrar
                </button>
            </form>

            <div class="text-center mt-4">
                <a class="btn btn-secondary btn-sm" href="<?= BASE_URL ?>/index.php?view=public/search_qsl">
                    ← Volver a búsqueda QSL
                </a>
            </div>
        </div>
    </div>
</body>

</html>
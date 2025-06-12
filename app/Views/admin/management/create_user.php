<?php
require_once(__DIR__ . '/../../../../config/constants.php');

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php?view=admin/management/login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>CREAR USUARIO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/styles/create_user.css">

    <script>
        const baseUrl = "<?= BASE_URL ?>";

        function confirmLogout() {
            if (confirm("¿Estás seguro de que deseas cerrar sesión?")) {
                window.location.href = baseUrl + "/index.php?action=logout";
            }
        }
    </script>

</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark bg-opacity-90 px-4">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <span class="navbar-text text-white fw-bold">QSL virtual Ecuador</span>
            <a href="#" onclick="confirmLogout()" class="btn btn-danger">
                <i class="bi bi-box-arrow-right"></i> Cerrar sesión
            </a>
        </div>
    </nav>

    <div class="container py-5">
        <div class="form-box mx-auto">
            <h2 class="text-center mb-4">CREAR USUARIO</h2>

            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-success text-center"><?= htmlspecialchars($_GET['msg']) ?></div>
            <?php endif; ?>

            <form action="index.php?action=create_user" method="POST">
                <div class="mb-3">
                    <label class="form-label">Nombre de usuario</label>
                    <input type="text" name="username" class="form-control" placeholder="Ej: admin123" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <input type="hidden" name="role" value="admin">

                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-person-plus-fill"></i> Crear Usuario
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="<?= BASE_URL ?>/index.php?view=admin/management/users" class="btn btn-secondary">
                    ← Volver a la gestión
                </a>
            </div>
        </div>
    </div>

</body>

</html>
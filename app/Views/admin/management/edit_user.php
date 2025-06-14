<?php
require_once(__DIR__ . '/../../../../config/constants.php');

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php?view=admin/management/login');
    exit();
}
require_once(__DIR__ . '/../../../Models/UserModel.php');
$model = new UserModel();

$userId = $_GET['user_id'] ?? null;
if (!$userId || !ctype_digit($userId)) {
    header("Location: index.php?view=admin/management/users&msg=Usuario inválido");
    exit;
}

$user = $model->getUserById($userId);
if (!$user) {
    header("Location: index.php?view=admin/management/users&msg=Usuario no encontrado");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Estilo personalizado -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/styles/edit_user.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</head>

<body>

    <!-- Navbar -->
    <?php include(APP_PATH . 'app/Include/navbar.php'); ?>


    <div class="container py-5">
        <div class="form-box mx-auto">
            <h2 class="text-center mb-4">✏️ Editar Usuario</h2>

            <form action="index.php?action=update_user" method="POST">
                <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">

                <div class="mb-3">
                    <label class="form-label">Usuario:</label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username_user']) ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Rol:</label>
                    <select name="role" class="form-select" required>
                        <option value="admin" <?= $user['role_user'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-save-fill"></i> Guardar Cambios
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
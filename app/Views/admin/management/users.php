<?php
require_once(__DIR__ . '/../../../../config/constants.php');

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php?view=admin/management/login');
    exit();
}
require_once(__DIR__ . '/../../../Models/UserModel.php');
$model = new UserModel();
$usuarios = $model->getAllUsers();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/styles/users.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</head>

<body>
    <!-- Navbar -->
    <?php include(APP_PATH . 'app/Include/navbar.php'); ?>


    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">👥 Gestión de Usuarios</h2>
            <a href="index.php?view=admin/management/create_user" class="btn btn-success">
                <i class="bi bi-person-plus-fill"></i> Nuevo Usuario Admin
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?= $u['user_id'] ?></td>
                            <td><?= htmlspecialchars($u['username_user']) ?></td>
                            <td><?= $u['role_user'] ?></td>
                            <td>
                                <?php if ($u['status_user']): ?>
                                    <span class="badge bg-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inhabilitado</span>
                                <?php endif; ?>
                            </td>
                            <td class="d-flex justify-content-center gap-2 flex-wrap">
                                <?php if ($u['status_user']): ?>
                                    <a href="index.php?view=admin/users/users&action=disable&user_id=<?= $u['user_id'] ?>" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-person-dash"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="index.php?view=admin/users/users&action=enable&user_id=<?= $u['user_id'] ?>" class="btn btn-outline-success btn-sm">
                                        <i class="bi bi-person-check"></i>
                                    </a>
                                <?php endif; ?>
                                <form action="index.php" method="GET">
                                    <input type="hidden" name="view" value="admin/management/edit_user">
                                    <input type="hidden" name="user_id" value="<?= $u['user_id'] ?>">
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <a href="<?= BASE_URL ?>/index.php?view=admin/management/dashboard" class="btn btn-secondary">
                ← Volver al Dashboard
            </a>
        </div>
    </div>
</body>

</html>
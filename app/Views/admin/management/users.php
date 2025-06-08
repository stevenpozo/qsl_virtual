<?php
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
    <style>
        body {
            font-family: Arial;
            background: #f2f2f2;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background: #007bff;
            color: white;
        }

        form {
            margin-bottom: 30px;
        }

        input,
        select,
        button {
            padding: 8px;
            margin: 5px;
        }

        .deshabilitado {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h2>👥 Gestión de Usuarios</h2>

    <form action="index.php?action=create_user" method="POST">
        <input type="text" name="username" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <select name="role" required>
            <option value="">Rol</option>
            <option value="admin">Admin</option>
            <option value="editor">Editor</option>
        </select>
        <button type="submit">➕ Crear Usuario</button>
    </form>

    <table>
        <thead>
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
                    <td><?= $u['status_user'] ? 'Activo' : '<span class="deshabilitado">Inhabilitado</span>' ?></td>
                    <td>
                        <?php if ($u['status_user']): ?>
                            <a href="index.php?view=admin/users/users&action=disable&user_id=<?= $u['user_id'] ?>">🚫 Deshabilitar</a>
                        <?php else: ?>
                            <a href="index.php?view=admin/users/users&action=enable&user_id=<?= $u['user_id'] ?>">✅ Habilitar</a>
                        <?php endif; ?>
                        <form action="index.php" method="GET" style="display:inline;">
                            <input type="hidden" name="view" value="admin/management/edit_user">
                            <input type="hidden" name="user_id" value="<?= $u['user_id'] ?>">
                            <button type="submit">✏️ Editar</button>
                        </form>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>
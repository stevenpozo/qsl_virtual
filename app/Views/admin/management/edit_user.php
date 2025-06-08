<?php
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
    <style>
        body { font-family: Arial; background: #f2f2f2; padding: 20px; }
        form { background: white; padding: 20px; max-width: 400px; margin: auto; border-radius: 10px; }
        input, select, button { width: 100%; padding: 10px; margin: 10px 0; }
        button { background-color: #007bff; color: white; border: none; }
        a { text-decoration: none; display: inline-block; margin-top: 10px; }
    </style>
</head>
<body>
    <h2>✏️ Editar Usuario</h2>
    <form action="index.php?action=update_user" method="POST">
        <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
        <label>Usuario:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username_user']) ?>" required>

        <label>Rol:</label>
        <select name="role" required>
            <option value="admin" <?= $user['role_user'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="editor" <?= $user['role_user'] === 'editor' ? 'selected' : '' ?>>Editor</option>
        </select>

        <button type="submit">💾 Guardar Cambios</button>
        <a href="index.php?view=admin/management/users">🔙 Cancelar</a>
    </form>
</body>
</html>

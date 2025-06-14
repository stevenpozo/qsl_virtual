<?php
// Ruta absoluta
if (!defined('APP_PATH')) {
    define('APP_PATH', realpath(__DIR__ . '/../../') . '/');
}
require_once(APP_PATH . 'config/constants.php');

if (session_status() === PHP_SESSION_NONE) session_start();
?>

<nav class="navbar navbar-expand-lg navbar-dark px-4" style="background: rgba(0, 0, 0, 0.7);">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-light"
            href="<?= isset($_SESSION['username']) ? BASE_URL . '/index.php?view=admin/management/dashboard' : BASE_URL . '/index.php?view=public/search_qsl' ?>">
            <i class="bi bi-broadcast-pin me-2"></i> QSL Virtual Ecuador
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center">
                <?php if (isset($_SESSION['username'])): ?>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="<?= BASE_URL ?>/index.php?view=admin/management/dashboard">
                            <i class="bi bi-house-door me-1"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="<?= BASE_URL ?>/index.php?view=admin/events/list_events">
                            <i class="bi bi-calendar-event me-1"></i>Eventos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="<?= BASE_URL ?>/index.php?view=admin/statistics/statistics_events">
                            <i class="bi bi-bar-chart-line me-1"></i>Estadísticas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" onclick="confirmLogout()" class="nav-link text-danger fw-semibold">
                            <i class="bi bi-box-arrow-right me-1"></i>Salir
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-outline-success ms-2" href="<?= BASE_URL ?>/index.php?view=admin/management/login">
                            <i class="bi bi-person-circle me-1"></i> Iniciar sesión
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<script>
    function confirmLogout() {
        if (confirm("¿Estás seguro de que deseas cerrar sesión?")) {
            window.location.href = "<?= BASE_URL ?>/index.php?action=logout";
        }
    }
</script>

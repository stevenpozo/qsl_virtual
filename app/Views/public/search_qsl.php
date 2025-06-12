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
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- CSS personalizado -->
    <link href="/qsl_virtual/app/Views/styles/search_qsl.css" rel="stylesheet">

</head>

<body>
    <?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark bg-opacity-90 px-4">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <span class="navbar-text text-white fw-bold">QSL Virtual Ecuador</span>
            <a href="/qsl_virtual/index.php?view=admin/management/login" class="btn btn-success">
                <i class="bi bi-person-circle"></i> Login
            </a>
        </div>
    </nav>

    <!-- Contenido -->
    <div class="container py-5">
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($_GET['msg']) ?></div>
        <?php endif; ?>

        <form action="/qsl_virtual/index.php?action=search_logs" method="POST"
            class="form-container mx-auto">
            <h2 class="text-center text-black mb-4">QSL ECUADOR</h2>

            <div class="mb-4">
                <label for="event_id" class="form-label fw-semibold">
                    <i class="bi bi-calendar-event-fill me-1"></i>Seleccione un evento:
                </label>
                <select name="event_id" class="form-select" required>
                    <option value="">-- Seleccione --</option>
                    <?php foreach ($events as $event): ?>
                        <option value="<?= $event['event_id'] ?>">
                            <?= htmlspecialchars($event['name_event']) ?> (<?= $event['date_event'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="call" class="form-label fw-semibold">
                    <i class="bi bi-broadcast-pin me-1"></i>Ingrese su indicativo (CALL):
                </label>
                <input type="text" name="call" id="call" class="form-control" placeholder="Ej: PU1AJN" required
                    pattern="[A-Za-z0-9/]+" title="Solo letras, números y el signo /">
                <div id="callError" class="text-danger mt-1" style="display: none;">
                    Solo se permiten letras, números y el signo "/".
                </div>
            </div>


            <button type="submit" class="btn btn-primary w-100 btn-lg">
                <i class="bi bi-search"></i> Buscar registros
            </button>
        </form>
    </div>

    <script>
        const callInput = document.getElementById('call');
        const callError = document.getElementById('callError');
        const pattern = /^[A-Z0-9/]+$/;

        callInput.addEventListener('input', function() {
            const value = callInput.value;
            if (value === '' || pattern.test(value)) {
                callInput.classList.remove('is-invalid');
                callError.style.display = 'none';
            } else {
                callInput.classList.add('is-invalid');
                callError.style.display = 'block';
            }
        });
    </script>
</body>

</html>
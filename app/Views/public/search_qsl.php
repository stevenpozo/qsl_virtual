<?php
require_once(__DIR__ . '/../../Models/EventModel.php');
require_once(__DIR__ . '/../../../config/constants.php');

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
    <link rel="stylesheet" href="<?= BASE_URL ?>/styles/search_qsl.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</head>

<body>
    <?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

    <!-- Navbar -->
    <?php include(APP_PATH . 'app/Include/navbar.php'); ?>


    <!-- Contenido -->
    <div class="container py-5">
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($_GET['msg']) ?></div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/index.php?action=search_logs" method="POST"
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
                <input type="text" name="call" id="call" class="form-control" placeholder="Ej: PU1AJN" required>
                <div id="callError" class="text-danger mt-1 d-none">
                    Solo se permiten letras mayúsculas, números y el signo "/".
                </div>
            </div>

            <button type="submit" id="searchBtn" class="btn btn-primary w-100 btn-lg" disabled>
                <i class="bi bi-search"></i> Buscar registros
            </button>

        </form>
    </div>

    <script>
        const callInput = document.getElementById('call');
        const callError = document.getElementById('callError');
        const searchBtn = document.getElementById('searchBtn');
        const pattern = /^[A-Z0-9/]+$/;

        callInput.addEventListener('input', function() {
            // Convertir a mayúsculas automáticamente
            this.value = this.value.toUpperCase();

            if (pattern.test(this.value)) {
                callInput.classList.remove('is-invalid');
                callError.classList.add('d-none');
                searchBtn.disabled = false;
            } else {
                callInput.classList.add('is-invalid');
                callError.classList.remove('d-none');
                searchBtn.disabled = true;
            }
        });
    </script>

</body>

</html>
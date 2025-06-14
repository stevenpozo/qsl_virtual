<?php
require_once(__DIR__ . '/../../../../config/constants.php');
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php?view=admin/management/login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Evento</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Estilos personalizados -->
    <link href="<?= BASE_URL ?>/styles/crear_evento.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</head>

<body>
    <!-- Navbar -->
    <?php include(APP_PATH . 'app/Include/navbar.php'); ?>


    <!-- Contenido principal -->
    <div class="container py-5">
        <div class="form-box mx-auto">
            <h2 class="text-center mb-4">CREAR EVENTO</h2>

            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-danger text-center"><?= htmlspecialchars($_GET['msg']) ?></div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/index.php?action=create_event" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Nombre del Evento:</label>
                    <input type="text" name="name_event" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fecha del Evento:</label>
                    <input type="date" name="date_event" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Call Event:</label>
                    <input type="text" name="call_event" id="call_event" class="form-control" required>
                    <div id="call_event_feedback" class="form-text text-danger d-none"> Solo se permiten letras mayúsculas, números y el signo "/".
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Imagen (JPG):</label>
                    <input type="file" name="image_event" accept="image/jpeg" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Color del texto en diploma:</label>
                    <input type="color" name="color_event" class="form-control form-control-color" value="#000000" required>
                </div>

                <button type="submit" id="submit_btn" class="btn btn-primary w-100 btn" disabled>
                    <i class="bi bi-save-fill"></i> Guardar Evento
                </button>

            </form>

            <div class="text-center mt-4">
                <a href="<?= BASE_URL ?>/index.php?view=admin/events/list_events" class="btn btn-secondary">
                    ⬅ Volver a lista de eventos
                </a>
            </div>
        </div>
    </div>

    <script>
        const callInput = document.getElementById("call_event");
        const submitBtn = document.getElementById("submit_btn");
        const feedback = document.getElementById("call_event_feedback");

        function validateCallEvent(value) {
            // Solo permite letras mayúsculas y /
            const regex = /^[A-Z0-9/]+$/;
            return regex.test(value);
        }

        callInput.addEventListener("input", function() {
            // Convierte a mayúsculas automáticamente
            this.value = this.value.toUpperCase();

            if (validateCallEvent(this.value)) {
                submitBtn.disabled = false;
                feedback.classList.add("d-none");
            } else {
                submitBtn.disabled = true;
                feedback.classList.remove("d-none");
            }
        });
    </script>

</body>

</html>
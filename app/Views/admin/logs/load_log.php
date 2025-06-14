<?php
require_once(__DIR__ . '/../../../../config/constants.php');
require_once(__DIR__ . '/../../../Models/EventModel.php');

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php?view=admin/management/login");
    exit();
}

$model = new EventModel();
$eventId = $_GET['event_id'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Cargar Logs CSV/TXT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/styles/load_log.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
    <?php include(APP_PATH . 'app/Include/navbar.php'); ?>


    <div class="container py-5">
        <div class="form-box mx-auto">
            <h2 class="text-center mb-4">Cargar archivo .CSV o .TXT</h2>

            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-info text-center"><?= htmlspecialchars($_GET['msg']) ?></div>
            <?php endif; ?>

            <form id="logForm" action="<?= BASE_URL ?>/app/Controllers/LogController.php" method="post" enctype="multipart/form-data" onsubmit="return confirmSubmit()">
                <?php if ($eventId): ?>
                    <?php $evento = $model->getEventById($eventId); ?>
                    <p><strong>Evento seleccionado:</strong> <?= $evento['name_event'] ?> (<?= $evento['date_event'] ?>)</p>
                    <input type="hidden" name="event_id" id="event_id" value="<?= $evento['event_id'] ?>">
                <?php else: ?>
                    <div class="mb-3">
                        <label class="form-label">Seleccionar Evento:</label>
                        <select name="event_id" id="event_id" class="form-select" required>
                            <?php foreach ($model->getAllEvents() as $evento): ?>
                                <option value="<?= $evento['event_id'] ?>">
                                    <?= $evento['name_event'] ?> (<?= $evento['date_event'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">Archivo de logs (.csv o .txt):</label>
                    <input type="file" id="log_file" name="log_file" class="form-control" accept=".csv,.txt" required>
                </div>

                <button type="submit" id="submitBtn" class="btn btn-success w-100">
                    <i class="bi bi-upload"></i> Procesar Logs
                </button>
            </form>

            <div class="preview-box">
                <div id="summary" class="mb-3 text-center fw-semibold"></div>
                <div id="preview" class="table-responsive"></div>
            </div>

            <div class="text-center mt-4">
                <a href="<?= BASE_URL ?>/index.php?view=admin/events/list_events" class="btn btn-secondary">
                    ← Volver a la lista de eventos
                </a>
            </div>
        </div>
    </div>

    <script>
        function confirmSubmit() {
            return confirm('¿Estás seguro de que deseas subir estos logs?');
        }

        document.getElementById('log_file').addEventListener('change', async function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const eventId = document.getElementById('event_id').value;
            const reader = new FileReader();
            reader.onload = async function(e) {
                const lines = e.target.result.split(/\r?\n/);
                const headers = lines[0].split(';');
                const rows = lines.slice(1).filter(l => l.trim() !== '').map(line => {
                    const values = line.split(';');
                    const row = {};
                    headers.forEach((h, i) => row[h.trim()] = values[i]?.trim() || '');
                    return row;
                });

                const response = await fetch('<?= BASE_URL ?>/app/Controllers/check_duplicates.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        event_id: eventId,
                        logs: rows
                    })
                });

                const duplicates = await response.json();
                renderPreview(headers, rows, duplicates);
            };
            reader.readAsText(file);
        });

        function renderPreview(headers, rows, duplicates) {
            const container = document.getElementById('preview');
            const summaryBox = document.getElementById('summary');

            const duplicateCount = duplicates.filter(d => d.isDuplicate).length;
            const insertCount = rows.length - duplicateCount;

            summaryBox.innerHTML = `🔁 <strong>Total:</strong> ${rows.length} registros | ✅ <strong>A insertar:</strong> ${insertCount} | ⚠️ <strong>Duplicados:</strong> ${duplicateCount}`;

            let html = '<table class="table table-bordered table-striped">';
            html += '<thead><tr>' + headers.map(h => `<th>${h}</th>`).join('') + '<th>Duplicado</th></tr></thead><tbody>';

            rows.forEach((row, index) => {
                const isDup = duplicates.find(d => d.index === index)?.isDuplicate;
                html += '<tr class="' + (isDup ? 'table-danger' : '') + '">';
                headers.forEach(h => html += `<td>${row[h]}</td>`);
                html += `<td>${isDup ? 'Sí' : 'No'}</td>`;
                html += '</tr>';
            });

            html += '</tbody></table>';
            container.innerHTML = html;
        }
    </script>
</body>

</html>
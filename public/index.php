<?php
// Rutas permitidas
$rutas_validas = [
    'admin/events/list_events' => 'app/Views/admin/events/list_events.php',
    'admin/events/crear_evento' => 'app/Views/admin/events/crear_evento.php',
    'admin/events/edit_event' => 'app/Views/admin/events/edit_event.php',
    'admin/logs/subir_adi' => 'app/Views/admin/logs/subir_adi.php',
];

$view = $_GET['view'] ?? 'admin/events/list_events';


// Incluir el modelo base
require_once(__DIR__ . '/../app/Models/EventModel.php');

// Si es edición de evento vía POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_event'])) {
    require_once(__DIR__ . '/../app/Controllers/EventController.php');
    $controller = new EventController();
    $controller->editarEvento();
    exit;
}

// Mostrar la vista normalmente
if (array_key_exists($view, $rutas_validas)) {
    require_once(__DIR__ . '/../' . $rutas_validas[$view]);
} else {
    echo "<h2>❌ Vista no encontrada</h2>";
}

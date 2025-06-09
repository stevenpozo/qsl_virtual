<?php
require_once(__DIR__ . '/../Models/EventModel.php');

class EventController
{
    // Maneja la creación de un nuevo evento. Valida duplicados por nombre,
    // guarda la imagen en el servidor y registra el evento en la base de datos.
    public function createEvent()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name_event'];
            $date = $_POST['date_event'];
            $call = $_POST['call_event'];
            $color = $_POST['color_event'] ?? '#000000';
            $imageName = $_FILES['image_event']['name'];
            $imageTmp = $_FILES['image_event']['tmp_name'];

            $model = new EventModel();

            // Validar que no exista el mismo call_event
            // Validar que no exista el mismo name_event
            if ($model->existsCallSign($name)) {
                header("Location: /qsl_virtual/public/index.php?view=admin/events/crear_evento&msg=" . urlencode("⚠️ El nombre del evento '$name' ya está registrado."));
                exit;
            }


            // Guardar imagen
            $path = __DIR__ . '/../../uploads/' . $imageName;
            move_uploaded_file($imageTmp, $path);

            $success = $model->createEvent([
                'name_event' => $name,
                'date_event' => $date,
                'call_event' => $call,
                'image_event' => $imageName,
                'status_event' => 1,
                'color_event' => $color
            ]);

            if ($success) {
                header('Location: /qsl_virtual/public/index.php?view=admin/events/list_events&msg=' . urlencode('✅ Event created successfully.'));
            } else {
                header('Location: /qsl_virtual/public/index.php?view=admin/events/crear_evento&msg=' . urlencode('❌ Error saving event.'));
            }

            exit;
        }
    }


    // Permite la edición de un evento existente.
    // Actualiza datos como nombre, fecha, call sign, color, estado e imagen.
    // Si no se sube una nueva imagen, conserva la actual.
    public function editEvent()
    {
        if (isset($_POST['edit_event'])) {
            $id = $_POST['event_id'];
            $name = $_POST['name_event'];
            $date = $_POST['date_event'];
            $call = $_POST['call_event'];
            $status = $_POST['status_event'];
            $color = $_POST['color_event'] ?? '#000000';

            $model = new EventModel();
            $event = $model->getEventById($id);

            if ($_FILES['image_event']['error'] === UPLOAD_ERR_OK) {
                $imageName = $_FILES['image_event']['name'];
                $imageTmp = $_FILES['image_event']['tmp_name'];
                $path = __DIR__ . '/../../uploads/' . $imageName;
                move_uploaded_file($imageTmp, $path);
            } else {
                $imageName = $event['image_event'];
            }

            $model->updateEvent($id, $name, $date, $call, $imageName, $status, $color);
            header("Location: /qsl_virtual/public/index.php?view=admin/events/list_events");
            exit;
        }
    }

    // Muestra estadísticas por evento.
    // Recupera todos los eventos, calcula estadísticas por cada uno
    // y los envía a la vista de estadísticas.
    public function showStatistics()
    {
        $eventModel = new EventModel();
        $logModel = new LogModel();
        $events = $eventModel->getAllEvents();

        // Cargar estadísticas para cada evento
        foreach ($events as &$event) {
            $event['stats'] = $logModel->getStatsByEvent($event['event_id']);
        }

        // Exportar la variable para la vista
        include(__DIR__ . '/../Views/admin/statistics/statistics_events.php');
    }
}

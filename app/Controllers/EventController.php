<?php
require_once(__DIR__ . '/../Models/EventModel.php');

class EventController
{
    public function createEvent()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name_event'];
            $date = $_POST['date_event'];
            $call = $_POST['call_event'];
            $imageName = $_FILES['image_event']['name'];
            $imageTmp = $_FILES['image_event']['tmp_name'];

            $model = new EventModel();

            // Validar que no exista el mismo call_event
            if ($model->existsCallSign($call)) {
                header("Location: /qsl_virtual/public/index.php?view=admin/events/crear_evento&msg=" . urlencode("⚠️ El call sign '$call' ya está registrado."));
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
                'status_event' => 1
            ]);

            if ($success) {
                header('Location: /qsl_virtual/public/index.php?view=admin/events/list_events&msg=' . urlencode('✅ Event created successfully.'));
            } else {
                header('Location: /qsl_virtual/public/index.php?view=admin/events/crear_evento&msg=' . urlencode('❌ Error saving event.'));
            }

            exit;
        }
    }


    public function editEvent()
    {
        if (isset($_POST['edit_event'])) {
            $id = $_POST['event_id'];
            $name = $_POST['name_event'];
            $date = $_POST['date_event'];
            $call = $_POST['call_event'];
            $status = $_POST['status_event'];

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

            $model->updateEvent($id, $name, $date, $call, $imageName, $status);
            header("Location: /qsl_virtual/public/index.php?view=admin/events/list_events");
            exit;
        }
    }

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

<?php
require_once(__DIR__ . '/../Models/EventModel.php');

class EventController
{
    public function guardarEvento()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name_event'];
            $date = $_POST['date_event'];
            $call = $_POST['call_event'];
            $imageName = $_FILES['image_event']['name'];
            $imageTmp = $_FILES['image_event']['tmp_name'];

            // Subir imagen al directorio
            $ruta = __DIR__ . '/../../uploads/' . $imageName;
            move_uploaded_file($imageTmp, $ruta);

            // Guardar en BD
            $model = new EventModel();
            $success = $model->insertarEvento($name, $date, $call, $imageName);

            if ($success) {
                header('Location: ../Views/admin/list_events.php');
                exit;
            } else {
                echo "Error al guardar el evento.";
            }
        }
    }

    public function editarEvento()
    {
        if (isset($_POST['edit_event'])) {
            $id = $_POST['event_id'];
            $name = $_POST['name_event'];
            $date = $_POST['date_event'];
            $call = $_POST['call_event'];
            $status = $_POST['status_event'];

            $model = new EventModel();
            $evento = $model->obtenerEventoPorId($id);

            // Manejar imagen (nueva o actual)
            if ($_FILES['image_event']['error'] === UPLOAD_ERR_OK) {
                $imageName = $_FILES['image_event']['name'];
                $imageTmp = $_FILES['image_event']['tmp_name'];
                $ruta = __DIR__ . '/../../uploads/' . $imageName;
                move_uploaded_file($imageTmp, $ruta);
            } else {
                $imageName = $evento['image_event']; // mantener imagen actual
            }

            $model->actualizarEvento($id, $name, $date, $call, $imageName, $status);
            header("Location: /qsl_virtual/public/index.php?view=admin/events/list_events");
            exit;
        }
    }
}

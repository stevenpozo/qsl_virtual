<?php
require_once(__DIR__ . '/../Models/EventoModel.php');

class EventoController {
    public function guardarEvento() {
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
            $model = new EventoModel();
            $success = $model->insertarEvento($name, $date, $call, $imageName);

            if ($success) {
                header('Location: ../Views/admin/listar_eventos.php');
                exit;
            } else {
                echo "Error al guardar el evento.";
            }
        }
    }
}

// Llamar automáticamente si este archivo se accede directamente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new EventoController();
    $controller->guardarEvento();
}
?>

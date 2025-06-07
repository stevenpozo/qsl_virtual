<?php
require_once(__DIR__ . '/../Models/LogModel.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventId = $_POST['event_id'];
    $archivo = $_FILES['adi_file']['tmp_name'];

    $lineas = file($archivo);
    $modelo = new LogModel();

    foreach ($lineas as $linea) {
        if (strpos($linea, '<EOR>') !== false) {
            $call = extraerDato($linea, 'CALL');
            $date = extraerDato($linea, 'QSO_DATE');
            $time = extraerDato($linea, 'TIME_ON');
            $band = extraerDato($linea, 'BAND');
            $mode = extraerDato($linea, 'MODE');
            $rst_rcvd = extraerDato($linea, 'RST_RCVD');
            $rst_sent = extraerDato($linea, 'RST_SENT');

            if ($call && $date && $time) {
                $dateFormatted = substr($date, 0, 4) . "-" . substr($date, 4, 2) . "-" . substr($date, 6, 2);
                $utcFormatted = formatoHora($time);
                $rst = "$rst_rcvd,$rst_sent";

                $modelo->insertarLog($eventId, $call, $dateFormatted, $utcFormatted, $band, $mode, $rst);
            }
        }
    }

    header("Location: ../Views/admin/listar_eventos.php");
    exit;
}

function extraerDato($linea, $etiqueta) {
    $pattern = "/<$etiqueta:\d+>([^<]*)/";
    if (preg_match($pattern, $linea, $coincidencias)) {
        return trim($coincidencias[1]);
    }
    return null;
}

function formatoHora($hhmmss) {
    if (strlen($hhmmss) === 6) {
        $h = substr($hhmmss, 0, 2);
        $m = substr($hhmmss, 2, 2);
        $s = substr($hhmmss, 4, 2);
        return "$h:$m:$s";
    }
    return $hhmmss; // en caso de datos corruptos, lo dejamos como vino
}
?>

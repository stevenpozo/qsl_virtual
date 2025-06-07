<?php
require_once(__DIR__ . '/../Models/LogModel.php');
session_start();

class QslController
{
    public function searchLogs()
    {
        $eventId = $_POST['event_id'] ?? null;
        $callSign = strtoupper(trim($_POST['call'] ?? ''));

        if (!$eventId || !$callSign) {
            header("Location: /qsl_virtual/public/index.php?view=public/search_qsl&msg=" . urlencode("Missing data."));
            exit;
        }

        $logModel = new LogModel();
        $logs = $logModel->getLogsByEventAndCall($eventId, $callSign);

        if (count($logs) === 0) {
            header("Location: /qsl_virtual/public/index.php?view=public/search_qsl&msg=" . urlencode("❌ No records found for CALL '$callSign'"));
            exit;
        }

        $_SESSION['qsls'] = $logs;
        $_SESSION['call'] = $callSign;
        $_SESSION['event_id'] = $eventId;

        header("Location: /qsl_virtual/public/index.php?view=public/list_qsls");
        exit;
    }

    public function generateQslDiploma()
    {
        require_once(__DIR__ . '/../Libraries/fpdf/fpdf.php');
        if (!isset($_SESSION['qsls'], $_SESSION['event_id'])) {
            echo "No hay registros para generar el diploma.";
            exit;
        }

        $logs = $_SESSION['qsls'];
        $eventId = $_SESSION['event_id'];

        require_once(__DIR__ . '/../Models/EventModel.php');
        $eventModel = new EventModel();
        $event = $eventModel->getEventById($eventId);
        $background = __DIR__ . '/../../uploads/' . $event['image_event'];

        $pdf = new FPDF('L', 'mm', [140, 90]); // 14x9 cm
        $pdf->SetAutoPageBreak(false); // <== esto es clave

        $pdf->AddPage();

        // Imagen de fondo
        $pdf->Image($background, 0, 0, 140, 90);

        // Tabla sobre la imagen
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(5, 70); // Inicia tabla a los 70 mm de alto

        // Cabecera
        $pdf->Cell(35, 5, 'Confirming QSO with', 1);
        $pdf->Cell(20, 5, 'Date', 1);
        $pdf->Cell(15, 5, 'UTC', 1);
        $pdf->Cell(15, 5, 'Band', 1);
        $pdf->Cell(15, 5, 'Mode', 1);
        $pdf->Cell(30, 5, 'RST (sent/recv)', 1);
        $pdf->Ln();

        // Contenido limitado
        $pdf->SetFont('Arial', '', 5);
        $y = $pdf->GetY();
        $maxY = 86; // borde inferior menos 4 mm

        foreach ($logs as $log) {
            if ($y + 5 > $maxY) break; // solo imprime si no se pasa

            $pdf->SetX(5);
            $pdf->Cell(35, 5, $log['call_log'], 1);
            $pdf->Cell(20, 5, $log['date_log'], 1);
            $pdf->Cell(15, 5, $log['utc_log'], 1);
            $pdf->Cell(15, 5, $log['band_log'], 1);
            $pdf->Cell(15, 5, $log['mode_log'], 1);
            $pdf->Cell(30, 5, $log['rst_sent_log'] . ' / ' . $log['rst_rcvd_log'], 1);
            $pdf->Ln();
            $y += 5;
        }


        $pdf->Output('I', 'diploma_qsl.pdf');
        exit;
    }
}

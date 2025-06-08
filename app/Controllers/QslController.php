<?php
require_once(__DIR__ . '/../Models/LogModel.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

    public function generateSingleQslDiploma()
    {
        require_once(__DIR__ . '/../Libraries/fpdf/fpdf.php');
        require_once(__DIR__ . '/../Models/LogModel.php');
        require_once(__DIR__ . '/../Models/EventModel.php');

        $logId = $_POST['log_id'] ?? null;
        if (!$logId || !ctype_digit($logId)) {
            echo "ID de log inválido.";
            exit;
        }

        $logModel = new LogModel();
        $log = $logModel->getLogById($logId);
        if (!$log) {
            echo "Log no encontrado.";
            exit;
        }

        $eventModel = new EventModel();
        $event = $eventModel->getEventById($log['event_id']);
        $hexColor = $event['color_event'] ?? '#000000';
        list($r, $g, $b) = sscanf($hexColor, "#%02x%02x%02x");

        $background = __DIR__ . '/../../uploads/' . $event['image_event'];
        if (ob_get_length()) ob_end_clean();

        $pdf = new FPDF('L', 'mm', [140, 90]);
        $pdf->SetAutoPageBreak(false);
        $pdf->AddPage();
        $pdf->Image($background, 0, 0, 140, 90);

        $pdf->SetXY(5, 74);
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor($r, $g, $b);
        $pdf->SetX(5);

        $pdf->Cell(32, 5, $log['call_log'], 0, 0, 'C');
        $pdf->Cell(20, 5, $log['date_log'], 0, 0, 'C');
        $pdf->Cell(20, 5, $log['utc_log'], 0, 0, 'C');
        $pdf->Cell(19, 5, $log['band_log'], 0, 0, 'C');
        $pdf->Cell(19, 5, $log['mode_log'], 0, 0, 'C');
        $pdf->Cell(19, 5, $log['rst_sent_log'] . ' , ' . $log['rst_rcvd_log'], 0, 0, 'C');

        $pdf->Output('I', 'diploma_qsl_individual.pdf');
        exit;
    }
}

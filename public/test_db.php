<?php
require_once("config/db.php");

$db = new Database();
$conn = $db->getConnection();

if ($conn) {
    echo "<h3>✅ Conexión exitosa a la base de datos.</h3>";
} else {
    echo "<h3>❌ No se pudo conectar.</h3>";
}
?>

<?php
/**
 * Archivo de constantes globales para rutas dinámicas.
 * Define BASE_URL según el entorno (local o producción)
 */

// Obtener host
$host = $_SERVER['HTTP_HOST'] ?? '';
$base = '';

// Detecta si estás trabajando localmente (localhost o 127.0.0.1)
if (str_contains($host, 'localhost') || str_contains($host, '127.0.0.1')) {
    $base = '/QSL_VIRTUAL'; // Cambia esto si tu carpeta se llama distinto
}

// Constante global que puedes usar en todas tus vistas
define('BASE_URL', $base);

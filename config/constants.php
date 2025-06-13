<?php
/**
 * Archivo de constantes globales para rutas dinámicas.
 * Define BASE_URL según el entorno (local o producción)
 */

// Obtener host
$host = $_SERVER['HTTP_HOST'] ?? '';
$base = '';

if (str_contains($host, 'localhost') || str_contains($host, '127.0.0.1')) {
    $base = '/QSL_VIRTUAL'; // Solo en entorno local
} else {
    $base = ''; // Producción: raíz del dominio
}

define('BASE_URL', $base);


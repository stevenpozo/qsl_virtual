<?php
/**
 * Archivo de constantes globales para rutas dinámicas.
 * Define BASE_URL y APP_PATH para rutas absolutas del sistema.
 */

// Ruta absoluta al raíz del proyecto
if (!defined('APP_PATH')) {
    define('APP_PATH', realpath(__DIR__ . '/../') . '/');
}

// BASE_URL dinámico según el entorno
$host = $_SERVER['HTTP_HOST'] ?? '';
$base = '';

if (str_contains($host, 'localhost') || str_contains($host, '127.0.0.1')) {
    $base = '/QSL_VIRTUAL'; // Solo en entorno local
} else {
    $base = ''; // Producción: raíz del dominio
}

define('BASE_URL', $base);

<?php
/**
 * Konfigurasi Aplikasi SPK Penentuan Bahan Baju Konveksi Mas Ari
 * Skripsi - Agung Adhyaksa (NPM: 202243500428)
 */

if (!defined('BASE_URL')) {
    // Deteksi base url otomatis
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $baseUrl = rtrim($protocol . $host . $scriptDir, '/') . '/';
    define('BASE_URL', $baseUrl);
}

define('APP_NAME', 'SPK Bahan Baju - Konveksi Mas Ari');
define('APP_TITLE', 'Sistem Pendukung Keputusan Penentuan Bahan Baju');
define('APP_AUTHOR', 'Agung Adhyaksa');
define('APP_NPM', '202243500428');
define('APP_INSTITUTION', 'Universitas Indraprasta PGRI');
define('KONVEKSI_NAME', 'Konveksi Mas Ari');
define('KONVEKSI_ADDRESS', 'Jl. Gondangdia Baru No.26, Kel. Jaticempaka, Kec. Pondokgede, Kota Bekasi');

// Kuota default bahan baju terpilih
define('DEFAULT_KUOTA', 10);


<?php
/**
 * Database Singleton Handler menggunakan PDO
 * Dilengkapi Smart Auto-Retry Password & Friendly Error Handler
 */

class Database {
    private static ?PDO $instance = null;
    private static ?string $errorMessage = null;

    private function __construct() {}
    private function __clone() {}

    public static function getConnection(): ?PDO {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../config/database.php';
            $host = $config['host'];
            $port = $config['port'];
            $dbName = $config['database'];
            $charset = $config['charset'];
            $user = $config['username'];
            $configuredPass = $config['password'];
            $options = $config['options'];

            // Daftar password yang akan dicoba (prioritas: password di config, lalu common docker password)
            $passwordsToTry = array_unique([$configuredPass, 'root', '', 'password', 'admin', '123456', 'secret']);

            $connected = false;
            $lastException = null;

            foreach ($passwordsToTry as $pass) {
                $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset={$charset}";
                try {
                    self::$instance = new PDO($dsn, $user, $pass, $options);
                    $connected = true;
                    break;
                } catch (PDOException $e) {
                    $lastException = $e;
                    // Jika error bukan karena auth (misal database tidak ditemukan), jangan coba password lain
                    if ($e->getCode() == 1049) {
                        break;
                    }
                }
            }

            if (!$connected && $lastException) {
                if ($lastException->getCode() == 1049) {
                    self::$errorMessage = "Basis data '{$dbName}' belum dibuat di phpMyAdmin (localhost:8080). Silakan buat database baru dengan nama '{$dbName}', lalu import file 'database/db_spk_konveksi_masari.sql'.";
                } elseif ($lastException->getCode() == 1045) {
                    self::$errorMessage = "Kata sandi MySQL untuk user '{$user}' tidak sesuai. Silakan buka file 'config/database.php' lalu isi bagian 'password' sesuai kata sandi MySQL / Docker Anda.";
                } else {
                    self::$errorMessage = $lastException->getMessage();
                }
                return null;
            }
        }
        return self::$instance;
    }

    public static function getErrorMessage(): ?string {
        return self::$errorMessage;
    }
}

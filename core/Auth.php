<?php
/**
 * Auth Helper & Session Manager
 */

class Auth {
    public static function init(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function check(): bool {
        self::init();
        return isset($_SESSION['user_id']);
    }

    public static function user(): ?array {
        self::init();
        if (!self::check()) {
            return null;
        }
        return [
            'id'       => $_SESSION['user_id'] ?? null,
            'nama'     => $_SESSION['user_nama'] ?? '',
            'username' => $_SESSION['user_username'] ?? '',
            'role'     => $_SESSION['user_role'] ?? 'admin',
        ];
    }

    public static function login(array $user): void {
        self::init();
        $_SESSION['user_id']       = $user['id_user'];
        $_SESSION['user_nama']     = $user['nama_lengkap'];
        $_SESSION['user_username'] = $user['username'];
        $_SESSION['user_role']     = $user['role'];
    }

    public static function logout(): void {
        self::init();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    public static function requireLogin(): void {
        if (!self::check()) {
            $_SESSION['flash_error'] = 'Silakan login terlebih dahulu untuk mengakses sistem.';
            header('Location: ' . BASE_URL . '?page=login');
            exit;
        }
    }

    public static function setFlash(string $type, string $message): void {
        self::init();
        $_SESSION['flash_' . $type] = $message;
    }

    public static function getFlash(string $type): ?string {
        self::init();
        if (isset($_SESSION['flash_' . $type])) {
            $msg = $_SESSION['flash_' . $type];
            unset($_SESSION['flash_' . $type]);
            return $msg;
        }
        return null;
    }
}


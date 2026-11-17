<?php
/**
 * Front Controller & Routing System
 * SPK Penentuan Bahan Baju Konveksi Mas Ari Kota Bekasi - Simple Additive Weighting (SAW)
 * Agung Adhyaksa (NPM: 202243500428) - Universitas Indraprasta PGRI (UNINDRA)
 */

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/core/Auth.php';

$page = $_GET['page'] ?? (Auth::check() ? 'dashboard' : 'login');
$action = $_GET['action'] ?? 'index';

switch ($page) {
    case 'login':
        require_once __DIR__ . '/controllers/AuthController.php';
        (new AuthController())->login();
        break;

    case 'logout':
        require_once __DIR__ . '/controllers/AuthController.php';
        (new AuthController())->logout();
        break;

    case 'dashboard':
        require_once __DIR__ . '/controllers/DashboardController.php';
        (new DashboardController())->index();
        break;

    case 'bahan':
        require_once __DIR__ . '/controllers/BahanController.php';
        $c = new BahanController();
        if ($action === 'create') {
            $c->create();
        } elseif ($action === 'edit') {
            $c->edit();
        } elseif ($action === 'delete') {
            $c->delete();
        } else {
            $c->index();
        }
        break;

    case 'kriteria':
        require_once __DIR__ . '/controllers/KriteriaController.php';
        $c = new KriteriaController();
        if ($action === 'update_bobot') {
            $c->updateBobot();
        } else {
            $c->index();
        }
        break;

    case 'penilaian':
        require_once __DIR__ . '/controllers/PenilaianController.php';
        $c = new PenilaianController();
        if ($action === 'update') {
            $c->update();
        } else {
            $c->index();
        }
        break;

    case 'spk':
        require_once __DIR__ . '/controllers/SpkController.php';
        $c = new SpkController();
        if ($action === 'hasil') {
            $c->hasil();
        } elseif ($action === 'simpan') {
            $c->simpan();
        } elseif ($action === 'simulasi') {
            $c->simulasi();
        } else {
            $c->proses();
        }
        break;

    case 'laporan':
        require_once __DIR__ . '/controllers/LaporanController.php';
        $c = new LaporanController();
        if ($action === 'cetak') {
            $c->cetak();
        } elseif ($action === 'export') {
            $c->exportCsv();
        } elseif ($action === 'hapus') {
            $c->hapusRiwayat();
        } else {
            $c->riwayat();
        }
        break;

    default:
        if (Auth::check()) {
            header('Location: ' . BASE_URL . '?page=dashboard');
        } else {
            header('Location: ' . BASE_URL . '?page=login');
        }
        exit;
}


<?php
/**
 * Sidebar Navigation Layout
 */
$currentPage = $_GET['page'] ?? 'dashboard';
$user = Auth::user();
?>
<nav id="sidebar">
    <div class="brand">
        <div class="d-flex align-items-center">
            <div class="bg-primary text-white rounded-3 p-2 me-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                <i class="fa-solid fa-shirt"></i>
            </div>
            <div>
                <h5 class="mb-0">SPK BAHAN BAJU</h5>
                <small class="text-secondary"><?= htmlspecialchars(KONVEKSI_NAME) ?></small>
            </div>
        </div>
    </div>

    <div class="py-2">
        <div class="sidebar-heading">Menu Utama</div>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link <?= ($currentPage === 'dashboard') ? 'active' : '' ?>" href="<?= BASE_URL ?>?page=dashboard">
                    <i class="fa-solid fa-gauge-high"></i> Dashboard
                </a>
            </li>
        </ul>

        <div class="sidebar-heading">Master Data</div>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link <?= ($currentPage === 'kriteria') ? 'active' : '' ?>" href="<?= BASE_URL ?>?page=kriteria">
                    <i class="fa-solid fa-sliders"></i> Kriteria & Bobot (8)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($currentPage === 'bahan') ? 'active' : '' ?>" href="<?= BASE_URL ?>?page=bahan">
                    <i class="fa-solid fa-layer-group"></i> Bahan Baju (100)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($currentPage === 'penilaian') ? 'active' : '' ?>" href="<?= BASE_URL ?>?page=penilaian">
                    <i class="fa-solid fa-table-cells"></i> Penilaian Matriks
                </a>
            </li>
        </ul>

        <div class="sidebar-heading">Metode SAW</div>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link <?= ($currentPage === 'spk' && ($_GET['action'] ?? '') === 'proses') ? 'active' : '' ?>" href="<?= BASE_URL ?>?page=spk&action=proses">
                    <i class="fa-solid fa-calculator"></i> Proses Perhitungan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($currentPage === 'spk' && ($_GET['action'] ?? '') === 'hasil') ? 'active' : '' ?>" href="<?= BASE_URL ?>?page=spk&action=hasil">
                    <i class="fa-solid fa-trophy"></i> Hasil & Kuota Seleksi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($currentPage === 'spk' && ($_GET['action'] ?? '') === 'simulasi') ? 'active' : '' ?>" href="<?= BASE_URL ?>?page=spk&action=simulasi">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Simulasi Konsultasi
                </a>
            </li>
        </ul>

        <div class="sidebar-heading">Laporan</div>
        <ul class="nav flex-column mb-3">
            <li class="nav-item">
                <a class="nav-link" href="<?= BASE_URL ?>?page=laporan&action=cetak" target="_blank">
                    <i class="fa-solid fa-print"></i> Cetak Rekomendasi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= BASE_URL ?>?page=laporan&action=export">
                    <i class="fa-solid fa-file-excel"></i> Ekspor Excel (CSV)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($currentPage === 'laporan' && ($_GET['action'] ?? '') === 'riwayat') ? 'active' : '' ?>" href="<?= BASE_URL ?>?page=laporan&action=riwayat">
                    <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Keputusan
                </a>
            </li>
        </ul>

        <div class="px-3 pt-3 border-top border-secondary">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-secondary rounded-circle text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="text-white text-truncate fw-bold" style="font-size: 0.85rem;"><?= htmlspecialchars($user['nama'] ?? 'User') ?></div>
                    <div class="text-muted" style="font-size: 0.72rem;"><?= ucfirst(htmlspecialchars($user['role'] ?? 'Admin')) ?></div>
                </div>
            </div>
            <a href="<?= BASE_URL ?>?page=logout" class="btn btn-sm btn-outline-danger w-100 mt-1">
                <i class="fa-solid fa-right-from-bracket me-1"></i> Keluar
            </a>
        </div>
    </div>
</nav>

<div id="main-content" class="w-100">
    <header class="top-navbar">
        <div class="d-flex align-items-center">
            <span class="badge bg-light text-primary border me-2 fw-semibold">
                <i class="fa-solid fa-graduation-cap me-1"></i> TA Skripsi
            </span>
            <span class="text-secondary small d-none d-md-inline">
                <strong><?= htmlspecialchars(APP_AUTHOR) ?></strong> (NPM: <?= htmlspecialchars(APP_NPM) ?>) - <?= htmlspecialchars(APP_INSTITUTION) ?>
            </span>
        </div>
        <div class="d-flex align-items-center">
            <span class="text-muted small me-3">
                <i class="fa-regular fa-calendar me-1"></i> <?= date('d M Y') ?>
            </span>
            <span class="badge bg-primary">Metode SAW</span>
        </div>
    </header>
    <main class="content-body">


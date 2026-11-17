<?php
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="container-fluid p-0">
    <!-- Welcome Banner -->
    <div class="card border-0 rounded-4 mb-4 text-white shadow-sm" style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-white text-primary mb-2 px-3 py-2 fw-semibold">
                        <i class="fa-solid fa-sparkles me-1"></i> Sistem Pendukung Keputusan (SPK) SAW
                    </span>
                    <h3 class="fw-bold mb-1">Selamat Datang di Sistem Penentuan Bahan Baju</h3>
                    <p class="mb-0 text-light opacity-75">
                        Studi Kasus <strong><?= htmlspecialchars(KONVEKSI_NAME) ?></strong> Kota Bekasi. 
                        Sistem membantu pengambilan keputusan objektif berbasis algoritma <em>Simple Additive Weighting</em> dengan <strong>8 Kriteria</strong> dan <strong>100 Alternatif Bahan Kain</strong>.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <a href="<?= BASE_URL ?>?page=spk&action=hasil" class="btn btn-warning text-dark fw-bold px-4 py-2 rounded-3 shadow">
                        <i class="fa-solid fa-trophy me-1"></i> Lihat Hasil Rekomendasi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Total Alternatif</div>
                        <h4 class="fw-bold mb-0 text-dark"><?= $totalBahan ?> Bahan</h4>
                        <small class="text-success"><i class="fa-solid fa-check"></i> 100 Bahan Terdaftar</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-info bg-opacity-10 text-info me-3">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Kriteria Penilaian</div>
                        <h4 class="fw-bold mb-0 text-dark"><?= $totalKriteria ?> Kriteria</h4>
                        <small class="text-muted">7 Benefit, 1 Cost</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                        <i class="fa-solid fa-filter-circle-dollar"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Kuota Kebutuhan</div>
                        <h4 class="fw-bold mb-0 text-dark"><?= DEFAULT_KUOTA ?> Bahan</h4>
                        <small class="text-primary fw-semibold">Status: Layak Digunakan</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning me-3">
                        <i class="fa-solid fa-award"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Peringkat 1 Utama</div>
                        <h5 class="fw-bold mb-0 text-dark text-truncate" style="max-width: 140px;">
                            <?= !empty($topBahan[0]['nama_bahan']) ? htmlspecialchars($topBahan[0]['nama_bahan']) : 'Belum Ada' ?>
                        </h5>
                        <small class="text-warning fw-bold">Skor V: <?= !empty($topBahan[0]['nilai_v']) ? $topBahan[0]['nilai_v'] : '-' ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart & Top 5 Table -->
    <div class="row g-4">
        <!-- Chart Column -->
        <div class="col-lg-7">
            <div class="card card-table h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="fa-solid fa-chart-column text-primary me-2"></i> Grafik Skor Top 10 Bahan Baju (Terpilih)
                    </h6>
                    <span class="badge bg-primary bg-opacity-10 text-primary">Nilai Preferensi (V)</span>
                </div>
                <div class="card-body">
                    <canvas id="topChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Top 5 Table Column -->
        <div class="col-lg-5">
            <div class="card card-table h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="fa-solid fa-medal text-warning me-2"></i> Top 5 Bahan Rekomendasi
                    </h6>
                    <a href="<?= BASE_URL ?>?page=spk&action=hasil" class="btn btn-sm btn-outline-primary">
                        Lihat 100 <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="text-center" width="60">Rank</th>
                                <th>Bahan Baju</th>
                                <th class="text-center">Skor V</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($topBahan)): ?>
                                <?php foreach ($topBahan as $item): ?>
                                    <tr>
                                        <td class="text-center">
                                            <?php if ($item['peringkat'] == 1): ?>
                                                <span class="badge bg-warning text-dark"><i class="fa-solid fa-crown"></i> 1</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary"><?= $item['peringkat'] ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($item['nama_bahan']) ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($item['kode_bahan']) ?> | Rp <?= number_format($item['harga_referensi'], 0, ',', '.') ?></small>
                                        </td>
                                        <td class="text-center fw-bold text-primary">
                                            <?= number_format($item['nilai_v'], 4) ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-terpilih small">Terpilih</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Belum ada data hasil perhitungan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('topChart').getContext('2d');
    const labels = <?= json_encode($chartLabels) ?>;
    const scores = <?= json_encode($chartScores) ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Nilai Preferensi (V)',
                data: scores,
                backgroundColor: 'rgba(37, 99, 235, 0.85)',
                borderColor: '#1e40af',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: false,
                    min: 0.5,
                    max: 1.0,
                    ticks: {
                        stepSize: 0.1
                    }
                },
                x: {
                    ticks: {
                        font: { size: 10 }
                    }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>


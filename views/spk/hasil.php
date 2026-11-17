<?php
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$ranking = $saw['ranking'];
$topItem = $saw['top_alternatif'];
$currentQuota = $saw['kuota'];
?>

<div class="container-fluid p-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Hasil Perangkingan & Seleksi Kuota Bahan</h4>
            <p class="text-muted small mb-0">
                Peringkat 1 s.d 100 Rekomendasi Bahan Baju pada Konveksi Mas Ari Kota Bekasi Metode SAW.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>?page=spk&action=proses" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-calculator me-1"></i> Rincian Rumus
            </a>
            <a href="<?= BASE_URL ?>?page=laporan&action=cetak&kuota=<?= $currentQuota ?>" target="_blank" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-print me-1"></i> Cetak Laporan Rekomendasi
            </a>
        </div>
    </div>

    <?php if (!empty($flashSuccess)): ?>
        <div class="alert alert-success alert-dismissible fade show small" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($flashSuccess) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Peringkat 1 Highlight Card -->
    <?php if (!empty($topItem)): ?>
        <div class="card border-0 rounded-4 mb-4 text-white shadow-sm" style="background: linear-gradient(135deg, #d97706 0%, #b45309 100%);">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-warning text-dark px-3 py-2 fw-bold fs-6 me-2 shadow-sm">
                                <i class="fa-solid fa-crown me-1"></i> REKOMENDASI TERBAIK #1
                            </span>
                            <span class="badge bg-white bg-opacity-20 text-white">Status: Terpilih (Layak Digunakan)</span>
                        </div>
                        <h2 class="fw-bold mb-1"><?= htmlspecialchars($topItem['nama_bahan']) ?> (<?= htmlspecialchars($topItem['kode_bahan']) ?>)</h2>
                        <p class="mb-0 text-white-50">
                            <?= htmlspecialchars($topItem['deskripsi'] ?? 'Bahan dengan akumulasi nilai preferensi multi-kriteria tertinggi.') ?>
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <div class="d-inline-block bg-white text-dark rounded-4 p-3 shadow text-center">
                            <div class="small text-muted fw-bold text-uppercase">Nilai Preferensi (V)</div>
                            <div class="display-6 fw-bold text-primary mb-0"><?= number_format($topItem['nilai_v'], 5) ?></div>
                            <div class="small text-muted mt-1">Rp <?= number_format($topItem['harga_referensi'], 0, ',', '.') ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Filter Kuota & Save Snapshot Row -->
    <div class="card card-table mb-4">
        <div class="card-body py-3">
            <div class="row align-items-center g-3">
                <div class="col-md-6">
                    <form action="<?= BASE_URL ?>" method="GET" class="d-flex align-items-center gap-2">
                        <input type="hidden" name="page" value="spk">
                        <input type="hidden" name="action" value="hasil">
                        <label class="small fw-bold text-secondary text-nowrap">
                            <i class="fa-solid fa-filter me-1 text-primary"></i> Kuota Kebutuhan Bahan:
                        </label>
                        <select name="kuota" class="form-select form-select-sm w-auto fw-bold text-primary" onchange="this.form.submit()">
                            <option value="5" <?= ($currentQuota == 5) ? 'selected' : '' ?>>5 Bahan Terpilih</option>
                            <option value="10" <?= ($currentQuota == 10) ? 'selected' : '' ?>>10 Bahan Terpilih (Default Skripsi)</option>
                            <option value="15" <?= ($currentQuota == 15) ? 'selected' : '' ?>>15 Bahan Terpilih</option>
                            <option value="20" <?= ($currentQuota == 20) ? 'selected' : '' ?>>20 Bahan Terpilih</option>
                            <option value="25" <?= ($currentQuota == 25) ? 'selected' : '' ?>>25 Bahan Terpilih</option>
                        </select>
                        <span class="small text-muted">(Peringkat 1 s.d <?= $currentQuota ?> dinyatakan Layak)</span>
                    </form>
                </div>
                <div class="col-md-6 text-md-end">
                    <form action="<?= BASE_URL ?>?page=spk&action=simpan" method="POST" class="d-inline">
                        <input type="hidden" name="kuota" value="<?= $currentQuota ?>">
                        <input type="hidden" name="catatan" value="Penentuan stok bahan konveksi kuota <?= $currentQuota ?>">
                        <button type="submit" class="btn btn-sm btn-outline-success">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Riwayat Keputusan Ini
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Top 10 -->
    <div class="card card-table mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="fa-solid fa-chart-column text-primary me-2"></i> Perbandingan Skor Nilai Preferensi ($V$) Top 10 Alternatif
            </h6>
        </div>
        <div class="card-body">
            <canvas id="hasilChart" height="220"></canvas>
        </div>
    </div>

    <!-- Table of 100 Ranked Materials -->
    <div class="card card-table mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="fa-solid fa-list-ol text-primary me-2"></i> Rekapitulasi Perangkingan 100 Bahan Baju
            </h6>
            <span class="badge bg-light text-secondary border">Total 100 Alternatif</span>
        </div>
        <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light sticky-top">
                    <tr>
                        <th class="text-center" width="80">Peringkat</th>
                        <th class="text-center" width="90">Kode</th>
                        <th>Nama Bahan Baju (Kain)</th>
                        <th>Karakteristik / Deskripsi</th>
                        <th width="150" class="text-end">Harga Acuan</th>
                        <th width="160" class="text-center">Skor Akhir ($V_i$)</th>
                        <th width="200" class="text-center">Status Kelayakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ranking as $item): ?>
                        <tr class="<?= ($item['peringkat'] <= $currentQuota) ? 'table-primary table-opacity-10' : '' ?>">
                            <td class="text-center">
                                <?php if ($item['peringkat'] == 1): ?>
                                    <span class="badge bg-warning text-dark px-2 py-1"><i class="fa-solid fa-crown"></i> 1</span>
                                <?php elseif ($item['peringkat'] <= 3): ?>
                                    <span class="badge bg-info text-dark px-2 py-1">#<?= $item['peringkat'] ?></span>
                                <?php elseif ($item['peringkat'] <= $currentQuota): ?>
                                    <span class="badge bg-primary px-2 py-1">#<?= $item['peringkat'] ?></span>
                                <?php else: ?>
                                    <span class="text-muted fw-bold">#<?= $item['peringkat'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center fw-bold text-primary"><?= htmlspecialchars($item['kode_bahan']) ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($item['nama_bahan']) ?></div>
                            </td>
                            <td class="small text-muted"><?= htmlspecialchars($item['deskripsi']) ?></td>
                            <td class="text-end fw-semibold">
                                Rp <?= number_format($item['harga_referensi'], 0, ',', '.') ?>
                            </td>
                            <td class="text-center fw-bold text-primary fs-6">
                                <?= number_format($item['nilai_v'], 5) ?>
                            </td>
                            <td class="text-center">
                                <?php if ($item['peringkat'] <= $currentQuota): ?>
                                    <span class="badge badge-terpilih px-3 py-1">
                                        <i class="fa-solid fa-circle-check me-1"></i> Terpilih (Layak)
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-tidak-terpilih px-3 py-1">
                                        Tidak Terpilih
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('hasilChart').getContext('2d');
    const labels = <?= json_encode($chartLabels) ?>;
    const scores = <?= json_encode($chartScores) ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Nilai Preferensi (V)',
                data: scores,
                backgroundColor: [
                    '#d97706', '#2563eb', '#2563eb', '#3b82f6', '#3b82f6',
                    '#60a5fa', '#60a5fa', '#93c5fd', '#93c5fd', '#bfdbfe'
                ],
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
                    ticks: { stepSize: 0.1 }
                },
                x: {
                    ticks: { font: { size: 11 } }
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


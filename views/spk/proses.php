<?php
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$kriteria = $saw['kriteria'];
$bahan = $saw['bahan'];
$matriksX = $saw['matriks_x'];
$extremes = $saw['extremes'];
$matriksR = $saw['matriks_r'];
$matriksTerbobot = $saw['matriks_terbobot'];
$ranking = $saw['ranking'];
?>

<div class="container-fluid p-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Tahapan Perhitungan Metode SAW</h4>
            <p class="text-muted small mb-0">
                Transparansi pembentukan Matriks Keputusan ($X$), Normalisasi ($R$), dan Pembobotan ($V$) untuk <strong>100 Alternatif Bahan Baju</strong>.
            </p>
        </div>
        <div>
            <a href="<?= BASE_URL ?>?page=spk&action=hasil" class="btn btn-warning text-dark fw-bold btn-sm">
                <i class="fa-solid fa-trophy me-1"></i> Lihat Hasil Perangkingan & Kuota
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-pills mb-3 gap-2" id="sawTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-x-btn" data-bs-toggle="pill" data-bs-target="#tab-x" type="button" role="tab">
                1. Matriks Keputusan ($X$)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-extremes-btn" data-bs-toggle="pill" data-bs-target="#tab-extremes" type="button" role="tab">
                2. Nilai Max & Min
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-r-btn" data-bs-toggle="pill" data-bs-target="#tab-r" type="button" role="tab">
                3. Matriks Ternormalisasi ($R$)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-w-btn" data-bs-toggle="pill" data-bs-target="#tab-w" type="button" role="tab">
                4. Matriks Terbobot ($W \times R$)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-v-btn" data-bs-toggle="pill" data-bs-target="#tab-v" type="button" role="tab">
                5. Nilai Preferensi ($V_i$)
            </button>
        </li>
    </ul>

    <div class="tab-content" id="sawTabsContent">
        <!-- 1. Matriks Keputusan X -->
        <div class="tab-pane fade show active" id="tab-x" role="tabpanel">
            <div class="card card-table">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="fa-solid fa-table text-primary me-2"></i> Matriks Keputusan Awal ($X$) berukuran 100 &times; 8
                    </h6>
                    <span class="badge bg-light text-secondary border">Nilai Asli / Skala Kripsin ($x_{ij}$)</span>
                </div>
                <div class="table-responsive" style="max-height: 520px; overflow-y: auto;">
                    <table class="table table-hover table-bordered table-striped text-center align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th width="60">Kode</th>
                                <th class="text-start" style="min-width: 170px;">Nama Bahan Baju</th>
                                <?php foreach ($kriteria as $k): ?>
                                    <th>
                                        <?= htmlspecialchars($k['kode_kriteria']) ?><br>
                                        <small class="badge <?= ($k['jenis'] === 'benefit') ? 'badge-benefit' : 'badge-cost' ?>"><?= ucfirst($k['jenis']) ?></small>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bahan as $bId => $b): ?>
                                <tr>
                                    <td class="fw-bold text-primary"><?= htmlspecialchars($b['kode_bahan']) ?></td>
                                    <td class="text-start fw-semibold"><?= htmlspecialchars($b['nama_bahan']) ?></td>
                                    <?php foreach ($kriteria as $kId => $k): ?>
                                        <td><?= number_format($matriksX[$bId][$kId] ?? 0, 2) ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 2. Nilai Max & Min -->
        <div class="tab-pane fade" id="tab-extremes" role="tabpanel">
            <div class="card card-table">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="fa-solid fa-arrows-up-down text-info me-2"></i> Nilai Maksimum dan Minimum Kolom Kriteria
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kriteria</th>
                                <th>Nama Kriteria</th>
                                <th>Sifat Kriteria</th>
                                <th>Nilai Maksimum $\max_i(x_{ij})$</th>
                                <th>Nilai Minimum $\min_i(x_{ij})$</th>
                                <th>Rumus Normalisasi yang Digunakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($kriteria as $kId => $k): ?>
                                <tr>
                                    <td class="fw-bold text-primary"><?= htmlspecialchars($k['kode_kriteria']) ?></td>
                                    <td class="text-start fw-semibold"><?= htmlspecialchars($k['nama_kriteria']) ?></td>
                                    <td>
                                        <span class="badge <?= ($k['jenis'] === 'benefit') ? 'badge-benefit' : 'badge-cost' ?>">
                                            <?= ucfirst($k['jenis']) ?>
                                        </span>
                                    </td>
                                    <td class="fw-bold text-success"><?= number_format($extremes[$kId]['max'], 2) ?></td>
                                    <td class="fw-bold text-danger"><?= number_format($extremes[$kId]['min'], 2) ?></td>
                                    <td class="text-start small">
                                        <?php if ($k['jenis'] === 'benefit'): ?>
                                            <code>r<sub>ij</sub> = x<sub>ij</sub> / <?= number_format($extremes[$kId]['max'], 2) ?></code> (Benefit: Nilai / Max)
                                        <?php else: ?>
                                            <code>r<sub>ij</sub> = <?= number_format($extremes[$kId]['min'], 2) ?> / x<sub>ij</sub></code> (Cost: Min / Nilai)
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 3. Matriks Ternormalisasi R -->
        <div class="tab-pane fade" id="tab-r" role="tabpanel">
            <div class="card card-table">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="fa-solid fa-arrows-spin text-success me-2"></i> Matriks Ternormalisasi ($R$)
                    </h6>
                    <span class="badge bg-light text-secondary border">Skala 0 s.d 1</span>
                </div>
                <div class="table-responsive" style="max-height: 520px; overflow-y: auto;">
                    <table class="table table-hover table-bordered table-striped text-center align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th width="60">Kode</th>
                                <th class="text-start" style="min-width: 170px;">Nama Bahan Baju</th>
                                <?php foreach ($kriteria as $k): ?>
                                    <th><?= htmlspecialchars($k['kode_kriteria']) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bahan as $bId => $b): ?>
                                <tr>
                                    <td class="fw-bold text-primary"><?= htmlspecialchars($b['kode_bahan']) ?></td>
                                    <td class="text-start fw-semibold"><?= htmlspecialchars($b['nama_bahan']) ?></td>
                                    <?php foreach ($kriteria as $kId => $k): ?>
                                        <td class="fw-semibold text-secondary"><?= number_format($matriksR[$bId][$kId] ?? 0, 4) ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 4. Matriks Terbobot W x R -->
        <div class="tab-pane fade" id="tab-w" role="tabpanel">
            <div class="card card-table">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="fa-solid fa-asterisk text-warning me-2"></i> Perkalian Matriks Ternormalisasi dengan Bobot ($w'_j \cdot r_{ij}$)
                    </h6>
                    <span class="badge bg-light text-secondary border">Vektor $W$: ∑ = 1.0000</span>
                </div>
                <div class="table-responsive" style="max-height: 520px; overflow-y: auto;">
                    <table class="table table-hover table-bordered table-striped text-center align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th width="60">Kode</th>
                                <th class="text-start" style="min-width: 170px;">Nama Bahan Baju</th>
                                <?php foreach ($kriteria as $k): ?>
                                    <th>
                                        <?= htmlspecialchars($k['kode_kriteria']) ?><br>
                                        <small class="text-muted fw-normal">w=<?= number_format($k['bobot_normalisasi'], 4) ?></small>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bahan as $bId => $b): ?>
                                <tr>
                                    <td class="fw-bold text-primary"><?= htmlspecialchars($b['kode_bahan']) ?></td>
                                    <td class="text-start fw-semibold"><?= htmlspecialchars($b['nama_bahan']) ?></td>
                                    <?php foreach ($kriteria as $kId => $k): ?>
                                        <td><?= number_format($matriksTerbobot[$bId][$kId] ?? 0, 4) ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 5. Nilai Preferensi V -->
        <div class="tab-pane fade" id="tab-v" role="tabpanel">
            <div class="card card-table">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="fa-solid fa-sigma text-primary me-2"></i> Nilai Preferensi Akhir ($V_i = \sum_{j=1}^8 w'_j \cdot r_{ij}$)
                    </h6>
                    <a href="<?= BASE_URL ?>?page=spk&action=hasil" class="btn btn-primary btn-sm">
                        Buka Halaman Perangkingan &raquo;
                    </a>
                </div>
                <div class="table-responsive" style="max-height: 520px; overflow-y: auto;">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th width="60" class="text-center">No</th>
                                <th width="80" class="text-center">Kode</th>
                                <th>Nama Bahan Baju</th>
                                <th width="180" class="text-center">Nilai Preferensi ($V_i$)</th>
                                <th width="140" class="text-center">Peringkat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ranking as $idx => $item): ?>
                                <tr>
                                    <td class="text-center text-muted small"><?= $idx + 1 ?></td>
                                    <td class="text-center fw-bold text-primary"><?= htmlspecialchars($item['kode_bahan']) ?></td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($item['nama_bahan']) ?></div>
                                        <small class="text-muted">Rp <?= number_format($item['harga_referensi'], 0, ',', '.') ?></small>
                                    </td>
                                    <td class="text-center fw-bold text-primary fs-6">
                                        <?= number_format($item['nilai_v'], 5) ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($item['peringkat'] == 1): ?>
                                            <span class="badge bg-warning text-dark"><i class="fa-solid fa-crown me-1"></i> Peringkat 1</span>
                                        <?php elseif ($item['peringkat'] <= 10): ?>
                                            <span class="badge bg-primary">Peringkat <?= $item['peringkat'] ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?= $item['peringkat'] ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>


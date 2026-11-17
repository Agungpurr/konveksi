<?php
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Data Kriteria & Bobot Penilaian</h4>
            <p class="text-muted small mb-0">
                Delapan (8) kriteria evaluasi bahan baju pada Konveksi Mas Ari Kota Bekasi sesuai Bab I & Bab III Skripsi.
            </p>
        </div>
        <div>
            <a href="<?= BASE_URL ?>?page=spk&action=proses" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-calculator me-1"></i> Uji Hitung SAW
            </a>
        </div>
    </div>

    <?php if (!empty($flashSuccess)): ?>
        <div class="alert alert-success alert-dismissible fade show small" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($flashSuccess) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($flashError)): ?>
        <div class="alert alert-danger alert-dismissible fade show small" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i><?= htmlspecialchars($flashError) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Form Update Bobot & Kriteria Table -->
    <div class="card card-table mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="fa-solid fa-sliders text-primary me-2"></i> Pengaturan 8 Kriteria & Vektor Bobot ($W$)
            </h6>
            <span class="badge bg-light text-dark border">
                Total Bobot: <strong><?= number_format($totalBobot, 2) ?></strong> (Ternormalisasi = 1.0000)
            </span>
        </div>
        <form action="<?= BASE_URL ?>?page=kriteria&action=update_bobot" method="POST">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" width="70">Kode</th>
                            <th>Nama Kriteria</th>
                            <th width="150" class="text-center">Sifat / Jenis</th>
                            <th width="160" class="text-center">Bobot Input ($w_j$)</th>
                            <th width="160" class="text-center">Bobot Ternormalisasi ($w'_j$)</th>
                            <th>Deskripsi / Tolok Ukur</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kriteriaList as $k): ?>
                            <tr>
                                <td class="text-center fw-bold text-primary">
                                    <?= htmlspecialchars($k['kode_kriteria']) ?>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark"><?= htmlspecialchars($k['nama_kriteria']) ?></span>
                                </td>
                                <td class="text-center">
                                    <select name="jenis[<?= $k['id_kriteria'] ?>]" class="form-select form-select-sm text-center">
                                        <option value="benefit" <?= ($k['jenis'] === 'benefit') ? 'selected' : '' ?>>Benefit</option>
                                        <option value="cost" <?= ($k['jenis'] === 'cost') ? 'selected' : '' ?>>Cost</option>
                                    </select>
                                </td>
                                <td class="text-center">
                                    <div class="input-group input-group-sm">
                                        <input type="number" step="0.01" min="0.01" name="bobot[<?= $k['id_kriteria'] ?>]" 
                                               class="form-control text-center fw-bold" 
                                               value="<?= htmlspecialchars($k['bobot']) ?>" required>
                                        <span class="input-group-text">pt</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary bg-opacity-10 text-primary fs-6 px-3 py-1">
                                        <?= number_format((float)$k['bobot_normalisasi'], 4) ?>
                                        <small class="text-muted">(<?= round((float)$k['bobot_normalisasi'] * 100, 1) ?>%)</small>
                                    </span>
                                </td>
                                <td class="small text-muted">
                                    <?= htmlspecialchars($k['keterangan']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="bg-light fw-bold">
                            <td colspan="3" class="text-end">Jumlah Total Bobot:</td>
                            <td class="text-center text-primary"><?= number_format($totalBobot, 2) ?> pt</td>
                            <td class="text-center text-success">1.0000 (100%)</td>
                            <td>
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan Bobot
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </form>
    </div>

    <!-- Sub-Kriteria / Kripsin Section -->
    <div class="card card-table">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="fa-solid fa-list-check text-info me-2"></i> Skala Penilaian Sub-Kriteria (Kripsin 1 s.d 5)
            </h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <?php foreach ($kriteriaList as $k): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="border rounded-3 p-3 h-100 bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-primary"><?= htmlspecialchars($k['kode_kriteria']) ?></span>
                                <span class="badge <?= ($k['jenis'] === 'benefit') ? 'badge-benefit' : 'badge-cost' ?>">
                                    <?= ucfirst($k['jenis']) ?>
                                </span>
                            </div>
                            <div class="fw-bold text-dark small mb-2"><?= htmlspecialchars($k['nama_kriteria']) ?></div>
                            <ul class="list-unstyled small mb-0">
                                <?php if (!empty($subKriteriaGroup[$k['id_kriteria']])): ?>
                                    <?php foreach ($subKriteriaGroup[$k['id_kriteria']] as $sub): ?>
                                        <li class="py-1 border-bottom d-flex justify-content-between">
                                            <span class="text-secondary"><?= htmlspecialchars($sub['nama_sub']) ?></span>
                                            <span class="badge bg-secondary"><?= (int)$sub['nilai_skala'] ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>


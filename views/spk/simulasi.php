<?php
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="container-fluid p-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Simulasi Konsultasi Pemilihan Bahan Baju</h4>
            <p class="text-muted small mb-0">
                Fitur konsultasi pelanggan interaktif: sesuaikan prioritas bobot untuk menghasilkan rekomendasi bahan kain yang sesuai pesanan khusus.
            </p>
        </div>
        <div>
            <a href="<?= BASE_URL ?>?page=spk&action=hasil" class="btn btn-outline-primary btn-sm">
                <i class="fa-solid fa-trophy me-1"></i> Kembali ke Ranking Master
            </a>
        </div>
    </div>

    <!-- Preset Buttons -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-light">
        <div class="card-body p-3">
            <span class="small fw-bold text-secondary me-2">
                <i class="fa-solid fa-wand-magic-sparkles text-warning me-1"></i> Preset Cepat Rekomendasi:
            </span>
            <div class="btn-group btn-group-sm flex-wrap gap-1 mt-2 mt-md-0" role="group">
                <button type="button" class="btn btn-outline-primary" onclick="setPreset('distro')">
                    <i class="fa-solid fa-shirt me-1"></i> Kaos Distro (Adem & Lembut)
                </button>
                <button type="button" class="btn btn-outline-success" onclick="setPreset('seragam')">
                    <i class="fa-solid fa-user-tie me-1"></i> Kemeja Seragam / PDH (Kuat & Bordir)
                </button>
                <button type="button" class="btn btn-outline-danger" onclick="setPreset('hemat')">
                    <i class="fa-solid fa-tags me-1"></i> Event Massal (Budget Hemat)
                </button>
                <button type="button" class="btn btn-outline-dark" onclick="setPreset('jaket')">
                    <i class="fa-solid fa-vest me-1"></i> Jaket & Hoodie (Tebal & Hangat)
                </button>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Input Form Bobot Simulasi -->
        <div class="col-lg-5">
            <div class="card card-table">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="fa-solid fa-sliders text-primary me-2"></i> Pengaturan Bobot Simulasi (8 Kriteria)
                    </h6>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>?page=spk&action=simulasi" method="POST" id="formSimulasi">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Batas Kuota Bahan Terpilih:</label>
                            <select name="kuota" class="form-select form-select-sm fw-bold text-primary">
                                <option value="5" <?= ($quota == 5) ? 'selected' : '' ?>>5 Bahan Rekomendasi</option>
                                <option value="10" <?= ($quota == 10) ? 'selected' : '' ?>>10 Bahan Rekomendasi (Standar)</option>
                                <option value="15" <?= ($quota == 15) ? 'selected' : '' ?>>15 Bahan Rekomendasi</option>
                            </select>
                        </div>

                        <div class="border rounded-3 p-3 bg-light mb-3">
                            <div class="small text-muted fw-bold mb-2">Tingkat Kepentingan Kriteria (Skala 1 - 25 pt):</div>
                            <?php foreach ($kriteriaList as $k): ?>
                                <?php 
                                    $val = $customBobot[$k['id_kriteria']] ?? $k['bobot'];
                                ?>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span class="fw-semibold text-dark">
                                            <?= htmlspecialchars($k['kode_kriteria']) ?> - <?= htmlspecialchars($k['nama_kriteria']) ?>
                                        </span>
                                        <span class="badge <?= ($k['jenis'] === 'benefit') ? 'badge-benefit' : 'badge-cost' ?>">
                                            <?= ucfirst($k['jenis']) ?>
                                        </span>
                                    </div>
                                    <div class="input-group input-group-sm">
                                        <input type="range" class="form-range me-2 flex-grow-1" min="1" max="30" step="1" 
                                               id="range_<?= $k['id_kriteria'] ?>" value="<?= (float)$val ?>"
                                               oninput="document.getElementById('input_<?= $k['id_kriteria'] ?>').value = this.value">
                                        <input type="number" name="bobot[<?= $k['id_kriteria'] ?>]" 
                                               id="input_<?= $k['id_kriteria'] ?>" class="form-control text-center fw-bold" 
                                               style="max-width: 70px;" value="<?= (float)$val ?>" min="1" max="50"
                                               oninput="document.getElementById('range_<?= $k['id_kriteria'] ?>').value = this.value">
                                        <span class="input-group-text">pt</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm">
                            <i class="fa-solid fa-calculator me-1"></i> Jalankan Simulasi Keputusan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Output Hasil Simulasi -->
        <div class="col-lg-7">
            <div class="card card-table">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="fa-solid fa-trophy text-warning me-2"></i> Rekomendasi Hasil Simulasi
                    </h6>
                    <?php if ($isSimulasi): ?>
                        <span class="badge bg-success bg-opacity-10 text-success">Kalkulasi Selesai</span>
                    <?php else: ?>
                        <span class="badge bg-light text-muted border">Belum Menjalankan Simulasi</span>
                    <?php endif; ?>
                </div>

                <?php if ($isSimulasi && !empty($hasilSimulasi)): ?>
                    <!-- Top 1 Banner -->
                    <?php $topSim = $hasilSimulasi['top_item']; ?>
                    <div class="p-3 bg-primary bg-opacity-10 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="fa-solid fa-crown fs-5"></i>
                            </div>
                            <div>
                                <small class="text-primary fw-bold text-uppercase">Bahan Paling Direkomendasikan:</small>
                                <h5 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($topSim['nama_bahan']) ?> (<?= htmlspecialchars($topSim['kode_bahan']) ?>)</h5>
                                <div class="small text-muted">
                                    Skor V: <strong><?= number_format($topSim['nilai_v'], 5) ?></strong> | Rp <?= number_format($topSim['harga_referensi'], 0, ',', '.') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th class="text-center" width="60">Rank</th>
                                    <th class="text-center" width="80">Kode</th>
                                    <th>Nama Bahan Baju</th>
                                    <th class="text-end" width="130">Harga</th>
                                    <th class="text-center" width="120">Skor V</th>
                                    <th class="text-center" width="140">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($hasilSimulasi['ranking'], 0, 20) as $item): ?>
                                    <tr class="<?= ($item['peringkat'] <= $hasilSimulasi['kuota']) ? 'table-primary table-opacity-10' : '' ?>">
                                        <td class="text-center">
                                            <?php if ($item['peringkat'] == 1): ?>
                                                <span class="badge bg-warning text-dark"><i class="fa-solid fa-crown"></i> 1</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary"><?= $item['peringkat'] ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center fw-bold text-primary"><?= htmlspecialchars($item['kode_bahan']) ?></td>
                                        <td>
                                            <span class="fw-bold text-dark"><?= htmlspecialchars($item['nama_bahan']) ?></span>
                                        </td>
                                        <td class="text-end small">Rp <?= number_format($item['harga_referensi'], 0, ',', '.') ?></td>
                                        <td class="text-center fw-bold text-primary"><?= number_format($item['nilai_v'], 5) ?></td>
                                        <td class="text-center">
                                            <?php if ($item['peringkat'] <= $hasilSimulasi['kuota']): ?>
                                                <span class="badge badge-terpilih small">Terpilih</span>
                                            <?php else: ?>
                                                <span class="badge badge-tidak-terpilih small">Tidak Terpilih</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="card-body text-center py-5 text-muted">
                        <i class="fa-solid fa-sliders fa-3x mb-3 text-secondary opacity-50"></i>
                        <h6 class="fw-bold">Belum Ada Hasil Simulasi</h6>
                        <p class="small text-muted mb-3" style="max-width: 400px; margin: 0 auto;">
                            Pilih salah satu preset cepat di atas atau sesuaikan slider kriteria, lalu klik tombol <strong>"Jalankan Simulasi Keputusan"</strong>.
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function setPreset(type) {
    // Kriteria ID:
    // 1: Daya Serap
    // 2: Keawetan
    // 3: Kelembutan
    // 4: Ketersediaan
    // 5: Warna
    // 6: Ketebalan
    // 7: Sablon/Bordir
    // 8: Harga
    let presets = {
        'distro':  { 1: 25, 2: 15, 3: 25, 4: 10, 5: 15, 6: 10, 7: 20, 8: 10 },
        'seragam': { 1: 10, 2: 25, 3: 10, 4: 15, 5: 15, 6: 20, 7: 25, 8: 10 },
        'hemat':   { 1: 5,  2: 15, 3: 5,  4: 25, 5: 15, 6: 10, 7: 15, 8: 30 },
        'jaket':   { 1: 15, 2: 25, 3: 20, 4: 15, 5: 10, 6: 25, 7: 15, 8: 10 }
    };

    let p = presets[type];
    if (p) {
        for (let kId in p) {
            let input = document.getElementById('input_' + kId);
            let range = document.getElementById('range_' + kId);
            if (input && range) {
                input.value = p[kId];
                range.value = p[kId];
            }
        }
        document.getElementById('formSimulasi').submit();
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>


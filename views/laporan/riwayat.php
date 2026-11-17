<?php
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Riwayat Keputusan SPK</h4>
            <p class="text-muted small mb-0">
                Daftar arsip kalkulasi penentuan bahan baju yang pernah disimpan ke basis data.
            </p>
        </div>
        <div>
            <a href="<?= BASE_URL ?>?page=spk&action=hasil" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-trophy me-1"></i> Perhitungan Baru
            </a>
        </div>
    </div>

    <?php if (!empty($flashSuccess)): ?>
        <div class="alert alert-success alert-dismissible fade show small" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($flashSuccess) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card card-table">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="60">No</th>
                        <th>Waktu Perhitungan</th>
                        <th>User Penginput</th>
                        <th>Bahan Terpilih #1</th>
                        <th class="text-center">Skor V</th>
                        <th class="text-center">Kuota</th>
                        <th>Catatan</th>
                        <th class="text-center" width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($riwayatList)): ?>
                        <?php $no = 1; foreach ($riwayatList as $r): ?>
                            <tr>
                                <td class="text-center text-muted small"><?= $no++ ?></td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= date('d M Y, H:i', strtotime($r['tanggal_hitung'])) ?> WIB</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border"><?= htmlspecialchars($r['nama_user'] ?? 'User') ?></span>
                                </td>
                                <td>
                                    <span class="fw-bold text-primary"><?= htmlspecialchars($r['nama_bahan'] ?? 'Bahan') ?></span>
                                    <small class="text-muted">(<?= htmlspecialchars($r['kode_bahan'] ?? '') ?>)</small>
                                </td>
                                <td class="text-center fw-bold text-success">
                                    <?= number_format($r['skor_terbaik'], 5) ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-terpilih"><?= (int)$r['kuota_terpilih'] ?> Bahan</span>
                                </td>
                                <td class="small text-muted">
                                    <?= htmlspecialchars($r['catatan'] ?? '-') ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= BASE_URL ?>?page=laporan&action=cetak&kuota=<?= (int)$r['kuota_terpilih'] ?>" target="_blank" class="btn btn-outline-primary" title="Cetak Rekomendasi">
                                            <i class="fa-solid fa-print"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?page=laporan&action=hapus&id=<?= $r['id_riwayat'] ?>" class="btn btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus arsip riwayat ini?');" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-clock-rotate-left fa-2x mb-2 d-block text-secondary"></i>
                                Belum ada riwayat keputusan yang tersimpan.<br>
                                <a href="<?= BASE_URL ?>?page=spk&action=hasil" class="btn btn-sm btn-primary mt-2">
                                    Simpan Perhitungan Sekarang
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>


<?php
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="container-fluid p-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Penilaian Matriks Keputusan ($X$)</h4>
            <p class="text-muted small mb-0">
                Pengisian nilai kinerja <strong>100 Alternatif Bahan Baju</strong> terhadap <strong>8 Kriteria</strong> (Skala Kripsin 1 s.d 5).
            </p>
        </div>
        <div>
            <a href="<?= BASE_URL ?>?page=spk&action=proses" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-calculator me-1"></i> Jalankan Perhitungan SAW
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

    <div class="card card-table mb-4">
        <!-- Search & Info Bar -->
        <div class="card-header bg-white py-3">
            <form action="<?= BASE_URL ?>" method="GET" class="row g-2 align-items-center">
                <input type="hidden" name="page" value="penilaian">
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="q" class="form-control" placeholder="Cari bahan baju..." value="<?= htmlspecialchars($search) ?>">
                        <button class="btn btn-primary" type="submit">Filter</button>
                        <?php if (!empty($search)): ?>
                            <a href="<?= BASE_URL ?>?page=penilaian" class="btn btn-outline-secondary">Reset</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-7 text-md-end text-muted small">
                    Halaman <?= $page ?> dari <?= max(1, $totalPages) ?> (Total <?= $totalItems ?> Bahan Aktif)
                </div>
            </form>
        </div>

        <form action="<?= BASE_URL ?>?page=penilaian&action=update" method="POST">
            <input type="hidden" name="redirect_query" value="<?= htmlspecialchars($_SERVER['QUERY_STRING'] ?? '') ?>">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0 text-center">
                    <thead class="table-light">
                        <tr>
                            <th rowspan="2" width="50" class="align-middle">No</th>
                            <th rowspan="2" width="70" class="align-middle">Kode</th>
                            <th rowspan="2" class="text-start align-middle" style="min-width: 180px;">Nama Bahan Baju</th>
                            <?php foreach ($kriteriaList as $k): ?>
                                <th class="align-middle">
                                    <div class="fw-bold text-primary"><?= htmlspecialchars($k['kode_kriteria']) ?></div>
                                    <small class="badge <?= ($k['jenis'] === 'benefit') ? 'badge-benefit' : 'badge-cost' ?>" style="font-size: 0.68rem;">
                                        <?= ucfirst($k['jenis']) ?>
                                    </small>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <?php foreach ($kriteriaList as $k): ?>
                                <th class="small text-muted fw-normal py-1" style="font-size: 0.72rem; min-width: 90px;">
                                    <?= htmlspecialchars($k['nama_kriteria']) ?>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($bahanList)): ?>
                            <?php $no = $offset + 1; foreach ($bahanList as $b): ?>
                                <tr>
                                    <td class="text-muted small"><?= $no++ ?></td>
                                    <td class="fw-bold text-primary"><?= htmlspecialchars($b['kode_bahan']) ?></td>
                                    <td class="text-start">
                                        <div class="fw-semibold text-dark"><?= htmlspecialchars($b['nama_bahan']) ?></div>
                                        <small class="text-muted">Rp <?= number_format($b['harga_referensi'], 0, ',', '.') ?></small>
                                    </td>
                                    <?php foreach ($kriteriaList as $k): ?>
                                        <?php 
                                            $currVal = $matriksNilai[$b['id_bahan']][$k['id_kriteria']] ?? 3.0;
                                        ?>
                                        <td>
                                            <select name="nilai[<?= $b['id_bahan'] ?>][<?= $k['id_kriteria'] ?>]" class="form-select form-select-sm text-center fw-bold">
                                                <option value="1" <?= ($currVal == 1) ? 'selected' : '' ?>>1</option>
                                                <option value="2" <?= ($currVal == 2) ? 'selected' : '' ?>>2</option>
                                                <option value="3" <?= ($currVal == 3) ? 'selected' : '' ?>>3</option>
                                                <option value="4" <?= ($currVal == 4) ? 'selected' : '' ?>>4</option>
                                                <option value="5" <?= ($currVal == 5) ? 'selected' : '' ?>>5</option>
                                            </select>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?= 3 + count($kriteriaList) ?>" class="text-center py-4 text-muted">
                                    Tidak ada data bahan untuk ditampilkan.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white d-flex flex-wrap justify-content-between align-items-center py-3 gap-2">
                <div>
                    <button type="submit" class="btn btn-success btn-sm px-4">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Nilai Matriks di Halaman Ini
                    </button>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= BASE_URL ?>?page=penilaian&p=<?= $page - 1 ?>&q=<?= urlencode($search) ?>">&laquo;</a>
                            </li>
                            <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= BASE_URL ?>?page=penilaian&p=<?= $i ?>&q=<?= urlencode($search) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= BASE_URL ?>?page=penilaian&p=<?= $page + 1 ?>&q=<?= urlencode($search) ?>">&raquo;</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>


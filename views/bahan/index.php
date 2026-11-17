<?php
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="container-fluid p-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Data Alternatif Bahan Baju</h4>
            <p class="text-muted small mb-0">
                Total <strong><?= $totalItems ?> Data Bahan Baku Kain</strong> pada Konveksi Mas Ari Kota Bekasi.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>?page=bahan&action=create" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus me-1"></i> Tambah Bahan Baru
            </a>
            <a href="<?= BASE_URL ?>?page=penilaian" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-table-cells me-1"></i> Input Matriks Nilai
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
                <input type="hidden" name="page" value="bahan">
                <div class="col-md-6 col-lg-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="q" class="form-control" placeholder="Cari nama bahan / kode..." value="<?= htmlspecialchars($search) ?>">
                        <button class="btn btn-primary" type="submit">Cari</button>
                        <?php if (!empty($search)): ?>
                            <a href="<?= BASE_URL ?>?page=bahan" class="btn btn-outline-secondary">Reset</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6 col-lg-8 text-md-end text-muted small">
                    Menampilkan <?= count($bahanList) ?> dari total <?= $totalItems ?> bahan (Halaman <?= $page ?> dari <?= max(1, $totalPages) ?>)
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center" width="70">No</th>
                        <th class="text-center" width="90">Kode</th>
                        <th>Nama Bahan Baju (Kain)</th>
                        <th>Karakteristik / Deskripsi</th>
                        <th width="150" class="text-end">Harga Acuan</th>
                        <th class="text-center" width="100">Status</th>
                        <th class="text-center" width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($bahanList)): ?>
                        <?php $no = $offset + 1; foreach ($bahanList as $b): ?>
                            <tr>
                                <td class="text-center text-muted small"><?= $no++ ?></td>
                                <td class="text-center fw-bold text-primary"><?= htmlspecialchars($b['kode_bahan']) ?></td>
                                <td>
                                    <span class="fw-bold text-dark"><?= htmlspecialchars($b['nama_bahan']) ?></span>
                                </td>
                                <td class="small text-muted">
                                    <?= htmlspecialchars($b['deskripsi'] ?? '-') ?>
                                </td>
                                <td class="text-end fw-semibold">
                                    Rp <?= number_format((float)$b['harga_referensi'], 0, ',', '.') ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($b['status'] === 'Aktif'): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= BASE_URL ?>?page=bahan&action=edit&id=<?= $b['id_bahan'] ?>" class="btn btn-outline-primary" title="Ubah Data">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?page=bahan&action=delete&id=<?= $b['id_bahan'] ?>" class="btn btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data bahan <?= htmlspecialchars($b['nama_bahan']) ?>?');" title="Hapus Data">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fa-solid fa-box-open fa-2x mb-2 d-block text-secondary"></i>
                                Data bahan baju tidak ditemukan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
                <div class="small text-muted">
                    Halaman <?= $page ?> dari <?= $totalPages ?>
                </div>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= BASE_URL ?>?page=bahan&p=<?= $page - 1 ?>&q=<?= urlencode($search) ?>">&laquo;</a>
                        </li>
                        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                            <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                <a class="page-link" href="<?= BASE_URL ?>?page=bahan&p=<?= $i ?>&q=<?= urlencode($search) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= BASE_URL ?>?page=bahan&p=<?= $page + 1 ?>&q=<?= urlencode($search) ?>">&raquo;</a>
                        </li>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>


<?php
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$isEdit = !empty($bahan['id_bahan']);
$title = $isEdit ? 'Ubah Data Bahan Baju' : 'Tambah Bahan Baju Baru';
$actionUrl = $isEdit ? BASE_URL . '?page=bahan&action=edit&id=' . $bahan['id_bahan'] : BASE_URL . '?page=bahan&action=create';
?>

<div class="container-fluid p-0">
    <div class="mb-3">
        <a href="<?= BASE_URL ?>?page=bahan" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Bahan
        </a>
        <h4 class="fw-bold mb-1 text-dark"><?= $title ?></h4>
        <p class="text-muted small mb-0">Kelola informasi bahan baku kain Konveksi Mas Ari Kota Bekasi.</p>
    </div>

    <div class="card card-table" style="max-width: 700px;">
        <div class="card-body p-4">
            <form action="<?= $actionUrl ?>" method="POST">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Kode Bahan <span class="text-danger">*</span></label>
                        <input type="text" name="kode_bahan" class="form-control" placeholder="Contoh: A101" 
                               value="<?= htmlspecialchars($bahan['kode_bahan'] ?? '') ?>" required>
                        <small class="text-muted">Kode unik alternatif</small>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label small fw-semibold">Nama Bahan Baju <span class="text-danger">*</span></label>
                        <input type="text" name="nama_bahan" class="form-control" placeholder="Contoh: Katun Combed 30s" 
                               value="<?= htmlspecialchars($bahan['nama_bahan'] ?? '') ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Harga Acuan per Satuan (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" step="1000" name="harga_referensi" class="form-control" placeholder="110000" 
                                   value="<?= htmlspecialchars($bahan['harga_referensi'] ?? 0) ?>">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Status Ketersediaan</label>
                        <select name="status" class="form-select">
                            <option value="Aktif" <?= (($bahan['status'] ?? 'Aktif') === 'Aktif') ? 'selected' : '' ?>>Aktif (Digunakan dalam Seleksi)</option>
                            <option value="Nonaktif" <?= (($bahan['status'] ?? '') === 'Nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold">Deskripsi / Karakteristik Bahan</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan karakteristik tekstur, kegunaan, atau sifat kain..."><?= htmlspecialchars($bahan['deskripsi'] ?? '') ?></textarea>
                    </div>

                    <div class="col-12 pt-3 border-top d-flex gap-2 justify-content-end">
                        <a href="<?= BASE_URL ?>?page=bahan" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Data Bahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>


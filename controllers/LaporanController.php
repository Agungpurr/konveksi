<?php
/**
 * LaporanController (Cetak Hasil Rekomendasi & Riwayat Keputusan)
 */

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/SAWEngine.php';

class LaporanController {
    public function cetak(): void {
        Auth::requireLogin();
        $quota = (int)($_GET['kuota'] ?? DEFAULT_KUOTA);
        if ($quota < 1) $quota = 10;

        $engine = new SAWEngine($quota);
        $saw = $engine->calculate();

        if (!$saw['status']) {
            die('Gagal memuat data laporan: ' . $saw['message']);
        }

        require __DIR__ . '/../views/laporan/cetak.php';
    }

    public function riwayat(): void {
        Auth::requireLogin();
        $db = Database::getConnection();

        $stmt = $db->query("
            SELECT r.*, u.nama_lengkap AS nama_user, b.kode_bahan, b.nama_bahan
            FROM riwayat_hasil r
            LEFT JOIN users u ON r.id_user = u.id_user
            LEFT JOIN bahan b ON r.id_bahan_terbaik = b.id_bahan
            ORDER BY r.tanggal_hitung DESC
        ");
        $riwayatList = $stmt->fetchAll();

        $flashSuccess = Auth::getFlash('success');
        $flashError = Auth::getFlash('error');

        require __DIR__ . '/../views/laporan/riwayat.php';
    }

    public function hapusRiwayat(): void {
        Auth::requireLogin();
        $db = Database::getConnection();
        $id = (int)($_GET['id'] ?? 0);

        if ($id > 0) {
            $stmt = $db->prepare("DELETE FROM riwayat_hasil WHERE id_riwayat = :id");
            $stmt->execute([':id' => $id]);
            Auth::setFlash('success', 'Riwayat keputusan berhasil dihapus.');
        }

        header('Location: ' . BASE_URL . '?page=laporan&action=riwayat');
        exit;
    }

    public function exportCsv(): void {
        Auth::requireLogin();
        $quota = (int)($_GET['kuota'] ?? DEFAULT_KUOTA);
        if ($quota < 1) $quota = 10;

        $engine = new SAWEngine($quota);
        $saw = $engine->calculate();

        if (!$saw['status']) {
            die('Gagal mengekspor data: ' . $saw['message']);
        }

        $filename = 'Hasil_Perangkingan_Bahan_Baju_SAW_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        // BOM UTF-8 for Excel compatibility
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Header
        fputcsv($output, [
            'Peringkat',
            'Kode Bahan',
            'Nama Bahan Baju',
            'Harga Acuan (Rp)',
            'Nilai Preferensi (V)',
            'Status Kelayakan (Kuota ' . $quota . ')',
            'Deskripsi'
        ]);

        foreach ($saw['ranking'] as $item) {
            fputcsv($output, [
                $item['peringkat'],
                $item['kode_bahan'],
                $item['nama_bahan'],
                $item['harga_referensi'],
                number_format($item['nilai_v'], 5, '.', ''),
                $item['status_kelayakan'],
                $item['deskripsi']
            ]);
        }

        fclose($output);
        exit;
    }
}


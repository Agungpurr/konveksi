<?php
/**
 * DashboardController
 */

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/SAWEngine.php';

class DashboardController {
    public function index(): void {
        Auth::requireLogin();
        $db = Database::getConnection();

        $totalBahan = 0;
        $totalKriteria = 0;
        $totalRiwayat = 0;
        $sawResult = null;
        $topBahan = [];
        $chartLabels = [];
        $chartScores = [];

        if ($db) {
            $totalBahan = (int)$db->query("SELECT COUNT(*) FROM bahan WHERE status = 'Aktif'")->fetchColumn();
            $totalKriteria = (int)$db->query("SELECT COUNT(*) FROM kriteria")->fetchColumn();
            $totalRiwayat = (int)$db->query("SELECT COUNT(*) FROM riwayat_hasil")->fetchColumn();

            // Jalankan engine SAW secara langsung untuk ringkasan real-time
            $engine = new SAWEngine(DEFAULT_KUOTA);
            $sawResult = $engine->calculate();

            if ($sawResult['status']) {
                $topBahan = array_slice($sawResult['ranking'], 0, 5);
                foreach (array_slice($sawResult['ranking'], 0, 10) as $item) {
                    $chartLabels[] = $item['kode_bahan'] . ' (' . $item['nama_bahan'] . ')';
                    $chartScores[] = $item['nilai_v'];
                }
            }
        }

        $user = Auth::user();
        require __DIR__ . '/../views/dashboard/index.php';
    }
}


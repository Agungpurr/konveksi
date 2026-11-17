<?php
/**
 * SpkController (Eksekusi SAW, Perhitungan Transparan, Perangkingan & Kuota)
 */

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/SAWEngine.php';

class SpkController {
    public function proses(): void {
        Auth::requireLogin();
        $quota = (int)($_GET['kuota'] ?? DEFAULT_KUOTA);
        $engine = new SAWEngine($quota);
        $saw = $engine->calculate();

        if (!$saw['status']) {
            Auth::setFlash('error', $saw['message']);
            header('Location: ' . BASE_URL . '?page=dashboard');
            exit;
        }

        require __DIR__ . '/../views/spk/proses.php';
    }

    public function hasil(): void {
        Auth::requireLogin();
        $quota = (int)($_GET['kuota'] ?? DEFAULT_KUOTA);
        if ($quota < 1) $quota = 10;

        $engine = new SAWEngine($quota);
        $saw = $engine->calculate();

        if (!$saw['status']) {
            Auth::setFlash('error', $saw['message']);
            header('Location: ' . BASE_URL . '?page=dashboard');
            exit;
        }

        $flashSuccess = Auth::getFlash('success');
        $flashError = Auth::getFlash('error');

        // Data chart top 10
        $chartLabels = [];
        $chartScores = [];
        foreach (array_slice($saw['ranking'], 0, 10) as $item) {
            $chartLabels[] = $item['kode_bahan'] . ' - ' . $item['nama_bahan'];
            $chartScores[] = $item['nilai_v'];
        }

        require __DIR__ . '/../views/spk/hasil.php';
    }

    public function simpan(): void {
        Auth::requireLogin();
        $db = Database::getConnection();
        $user = Auth::user();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $quota = (int)($_POST['kuota'] ?? DEFAULT_KUOTA);
            $catatan = trim($_POST['catatan'] ?? 'Rekomendasi pemilihan bahan konveksi');

            $engine = new SAWEngine($quota);
            $saw = $engine->calculate();

            if ($saw['status'] && !empty($saw['top_alternatif'])) {
                $topId = (int)$saw['top_alternatif']['id_bahan'];
                $topScore = (float)$saw['top_alternatif']['nilai_v'];
                $jsonData = json_encode([
                    'tanggal' => date('Y-m-d H:i:s'),
                    'kuota'   => $quota,
                    'top_10'  => array_slice($saw['ranking'], 0, 10),
                    'total'   => count($saw['ranking']),
                ]);

                $stmt = $db->prepare("
                    INSERT INTO riwayat_hasil (id_user, kuota_terpilih, id_bahan_terbaik, skor_terbaik, catatan, data_json)
                    VALUES (:u, :k, :b, :s, :c, :j)
                ");
                $stmt->execute([
                    ':u' => $user['id'],
                    ':k' => $quota,
                    ':b' => $topId,
                    ':s' => $topScore,
                    ':c' => $catatan,
                    ':j' => $jsonData
                ]);

                Auth::setFlash('success', 'Riwayat keputusan penentuan bahan baju berhasil disimpan ke database!');
            } else {
                Auth::setFlash('error', 'Gagal memproses kalkulasi untuk disimpan.');
            }
        }

        header('Location: ' . BASE_URL . '?page=spk&action=hasil&kuota=' . ($quota ?? DEFAULT_KUOTA));
        exit;
    }

    public function simulasi(): void {
        Auth::requireLogin();
        $db = Database::getConnection();

        // Ambil data 8 kriteria untuk form slider/bobot simulasi
        $kriteriaList = $db->query("SELECT * FROM kriteria ORDER BY id_kriteria ASC")->fetchAll();
        $customBobot = $_POST['bobot'] ?? [];
        $quota = (int)($_POST['kuota'] ?? 10);
        if ($quota < 1) $quota = 10;

        $isSimulasi = ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($customBobot));
        $hasilSimulasi = null;

        if ($isSimulasi) {
            // Jalankan simulasi dengan bobot kustom sementara (tanpa mengubah master database)
            $engine = new SAWEngine($quota);
            $baseResult = $engine->calculate();

            if ($baseResult['status']) {
                $matriksR = $baseResult['matriks_r'];
                $bahanMap = $baseResult['bahan'];

                // Hitung total bobot simulasi
                $totalCustom = 0.0;
                foreach ($kriteriaList as $k) {
                    $totalCustom += (float)($customBobot[$k['id_kriteria']] ?? $k['bobot']);
                }

                $wNormCustom = [];
                foreach ($kriteriaList as $k) {
                    $val = (float)($customBobot[$k['id_kriteria']] ?? $k['bobot']);
                    $wNormCustom[$k['id_kriteria']] = $totalCustom > 0 ? ($val / $totalCustom) : 0;
                }

                // Hitung nilai V kustom
                $simulasiRanking = [];
                foreach ($bahanMap as $bId => $b) {
                    $vCustom = 0.0;
                    foreach ($kriteriaList as $k) {
                        $kId = $k['id_kriteria'];
                        $vCustom += ($matriksR[$bId][$kId] ?? 0) * ($wNormCustom[$kId] ?? 0);
                    }
                    $simulasiRanking[] = [
                        'id_bahan'        => $bId,
                        'kode_bahan'      => $b['kode_bahan'],
                        'nama_bahan'      => $b['nama_bahan'],
                        'harga_referensi' => (float)$b['harga_referensi'],
                        'deskripsi'       => $b['deskripsi'],
                        'nilai_v'         => round($vCustom, 5),
                        'peringkat'       => 0,
                        'status_kelayakan'=> 'Tidak Terpilih'
                    ];
                }

                usort($simulasiRanking, function($a, $b) {
                    return ($b['nilai_v'] <=> $a['nilai_v']);
                });

                foreach ($simulasiRanking as $idx => &$item) {
                    $item['peringkat'] = $idx + 1;
                    if ($item['peringkat'] <= $quota) {
                        $item['status_kelayakan'] = 'Terpilih (Layak Digunakan)';
                    }
                }
                unset($item);

                $hasilSimulasi = [
                    'ranking'  => $simulasiRanking,
                    'top_item' => $simulasiRanking[0] ?? null,
                    'kuota'    => $quota
                ];
            }
        }

        require __DIR__ . '/../views/spk/simulasi.php';
    }
}


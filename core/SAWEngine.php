<?php
/**
 * Core Algorithm Engine: Simple Additive Weighting (SAW)
 * Studi Kasus: Penentuan Bahan Baju Konveksi Mas Ari Kota Bekasi
 * Peneliti: Agung Adhyaksa (NPM: 202243500428)
 */

require_once __DIR__ . '/Database.php';

class SAWEngine {
    private ?PDO $db;
    private int $quota;

    public function __construct(int $quota = 10) {
        $this->db = Database::getConnection();
        $this->quota = $quota > 0 ? $quota : 10;
    }

    /**
     * Jalankan proses komputasi lengkap metode SAW
     * Mengembalikan struktur data lengkap (Matriks X, Min/Max, Matriks R, Bobot W, Nilai V, Ranking, dan Kuota)
     */
    public function calculate(): array {
        if (!$this->db) {
            return [
                'status'  => false,
                'message' => 'Koneksi basis data belum terhubung: ' . Database::getErrorMessage()
            ];
        }

        // 1. Ambil seluruh data 8 Kriteria
        $stmtKriteria = $this->db->query("SELECT * FROM kriteria ORDER BY id_kriteria ASC");
        $kriteriaList = $stmtKriteria->fetchAll();

        if (empty($kriteriaList)) {
            return [
                'status'  => false,
                'message' => 'Data kriteria belum diatur di database.'
            ];
        }

        // Hitung total bobot dan bobot ternormalisasi w'_j
        $totalBobotInput = 0.0;
        foreach ($kriteriaList as $k) {
            $totalBobotInput += (float)$k['bobot'];
        }

        $kriteriaMap = [];
        $bobotTernormalisasi = [];
        foreach ($kriteriaList as $k) {
            $wNorm = $totalBobotInput > 0 ? ((float)$k['bobot'] / $totalBobotInput) : 0;
            $k['bobot_normalisasi'] = $wNorm;
            $kriteriaMap[$k['id_kriteria']] = $k;
            $bobotTernormalisasi[$k['id_kriteria']] = $wNorm;
        }

        // 2. Ambil seluruh Data Bahan (Alternatif)
        $stmtBahan = $this->db->query("SELECT * FROM bahan WHERE status = 'Aktif' ORDER BY id_bahan ASC");
        $bahanList = $stmtBahan->fetchAll();

        if (empty($bahanList)) {
            return [
                'status'  => false,
                'message' => 'Data alternatif bahan baju belum tersedia.'
            ];
        }

        $bahanMap = [];
        foreach ($bahanList as $b) {
            $bahanMap[$b['id_bahan']] = $b;
        }

        // 3. Ambil data nilai matriks keputusan (nilai_alternatif)
        $stmtNilai = $this->db->query("SELECT * FROM nilai_alternatif");
        $nilaiRows = $stmtNilai->fetchAll();

        // Bentuk Matriks Keputusan X[id_bahan][id_kriteria]
        $matriksX = [];
        foreach ($nilaiRows as $row) {
            $bId = (int)$row['id_bahan'];
            $kId = (int)$row['id_kriteria'];
            if (isset($bahanMap[$bId]) && isset($kriteriaMap[$kId])) {
                $matriksX[$bId][$kId] = (float)$row['nilai'];
            }
        }

        // Pastikan setiap bahan memiliki nilai untuk semua kriteria (default jika kosong = 1.0)
        foreach ($bahanMap as $bId => $b) {
            foreach ($kriteriaMap as $kId => $k) {
                if (!isset($matriksX[$bId][$kId])) {
                    $matriksX[$bId][$kId] = 1.0;
                }
            }
        }

        // 4. Cari nilai Maksimum (Max) dan Minimum (Min) untuk setiap kolom kriteria
        $extremes = [];
        foreach ($kriteriaMap as $kId => $k) {
            $columnValues = [];
            foreach ($bahanMap as $bId => $b) {
                $columnValues[] = $matriksX[$bId][$kId];
            }
            $maxVal = !empty($columnValues) ? max($columnValues) : 1.0;
            $minVal = !empty($columnValues) ? min($columnValues) : 1.0;

            $extremes[$kId] = [
                'max' => $maxVal > 0 ? $maxVal : 1.0,
                'min' => $minVal > 0 ? $minVal : 1.0,
            ];
        }

        // 5. Normalisasi Matriks R: r_ij
        // Benefit: r_ij = x_ij / Max(x_j)
        // Cost:    r_ij = Min(x_j) / x_ij
        $matriksR = [];
        foreach ($bahanMap as $bId => $b) {
            foreach ($kriteriaMap as $kId => $k) {
                $xVal = $matriksX[$bId][$kId];
                $jenis = strtolower($k['jenis']);

                if ($jenis === 'cost') {
                    $minVal = $extremes[$kId]['min'];
                    $rVal = $xVal > 0 ? ($minVal / $xVal) : 0;
                } else {
                    // Benefit
                    $maxVal = $extremes[$kId]['max'];
                    $rVal = $maxVal > 0 ? ($xVal / $maxVal) : 0;
                }

                $matriksR[$bId][$kId] = round($rVal, 4);
            }
        }

        // 6. Hitung Nilai Preferensi Akhir V_i = ∑ (w'_j * r_ij)
        $hasilRanking = [];
        $matriksTerbobot = [];

        foreach ($bahanMap as $bId => $b) {
            $totalV = 0.0;
            foreach ($kriteriaMap as $kId => $k) {
                $rVal = $matriksR[$bId][$kId];
                $wVal = $bobotTernormalisasi[$kId];
                $weightedVal = $rVal * $wVal;

                $matriksTerbobot[$bId][$kId] = round($weightedVal, 4);
                $totalV += $weightedVal;
            }

            $scoreFinal = round($totalV, 5);
            $hasilRanking[] = [
                'id_bahan'        => $bId,
                'kode_bahan'      => $b['kode_bahan'],
                'nama_bahan'      => $b['nama_bahan'],
                'harga_referensi' => (float)($b['harga_referensi'] ?? 0),
                'deskripsi'       => $b['deskripsi'] ?? '',
                'nilai_v'         => $scoreFinal,
                'peringkat'       => 0,
                'status_kelayakan'=> 'Tidak Terpilih'
            ];
        }

        // 7. Urutkan dari nilai V tertinggi ke terendah (Descending)
        usort($hasilRanking, function ($a, $b) {
            if ($b['nilai_v'] == $a['nilai_v']) {
                return strcmp($a['kode_bahan'], $b['kode_bahan']);
            }
            return ($b['nilai_v'] > $a['nilai_v']) ? 1 : -1;
        });

        // 8. Terapkan Peringkat dan Kuota Kebutuhan (misal kuota 10)
        foreach ($hasilRanking as $idx => &$item) {
            $rank = $idx + 1;
            $item['peringkat'] = $rank;
            if ($rank <= $this->quota) {
                $item['status_kelayakan'] = 'Terpilih (Layak Digunakan)';
            } else {
                $item['status_kelayakan'] = 'Tidak Terpilih';
            }
        }
        unset($item);

        return [
            'status'               => true,
            'kriteria'             => $kriteriaMap,
            'total_bobot_input'    => $totalBobotInput,
            'bobot_ternormalisasi' => $bobotTernormalisasi,
            'bahan'                => $bahanMap,
            'matriks_x'            => $matriksX,
            'extremes'             => $extremes,
            'matriks_r'            => $matriksR,
            'matriks_terbobot'     => $matriksTerbobot,
            'ranking'              => $hasilRanking,
            'kuota'                => $this->quota,
            'total_alternatif'     => count($bahanMap),
            'top_alternatif'       => $hasilRanking[0] ?? null
        ];
    }
}


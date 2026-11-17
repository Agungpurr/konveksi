<?php
/**
 * PenilaianController (Kelola Matriks Keputusan X - 100 Bahan x 8 Kriteria)
 */

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Database.php';

class PenilaianController {
    public function index(): void {
        Auth::requireLogin();
        $db = Database::getConnection();

        $search = trim($_GET['q'] ?? '');
        $page = max(1, (int)($_GET['p'] ?? 1));
        $perPage = 15;

        // Ambil 8 Kriteria
        $kriteriaList = $db->query("SELECT * FROM kriteria ORDER BY id_kriteria ASC")->fetchAll();

        // Ambil Sub-Kriteria untuk opsi dropdown
        $subKriteriaRows = $db->query("SELECT * FROM sub_kriteria ORDER BY id_kriteria ASC, nilai_skala ASC")->fetchAll();
        $subOptions = [];
        foreach ($subKriteriaRows as $s) {
            $subOptions[$s['id_kriteria']][] = $s;
        }

        // Filter Bahan
        $params = [];
        $whereSql = "WHERE status = 'Aktif'";
        if (!empty($search)) {
            $whereSql .= " AND (kode_bahan LIKE :s1 OR nama_bahan LIKE :s2)";
            $params[':s1'] = "%$search%";
            $params[':s2'] = "%$search%";
        }

        $stmtCount = $db->prepare("SELECT COUNT(*) FROM bahan $whereSql");
        $stmtCount->execute($params);
        $totalItems = (int)$stmtCount->fetchColumn();
        $totalPages = ceil($totalItems / $perPage);

        $offset = ($page - 1) * $perPage;
        $stmtBahan = $db->prepare("SELECT * FROM bahan $whereSql ORDER BY id_bahan ASC LIMIT $perPage OFFSET $offset");
        $stmtBahan->execute($params);
        $bahanList = $stmtBahan->fetchAll();

        // Ambil data nilai matriks untuk bahan di halaman ini
        $bahanIds = array_column($bahanList, 'id_bahan');
        $matriksNilai = [];
        if (!empty($bahanIds)) {
            $inClause = implode(',', array_fill(0, count($bahanIds), '?'));
            $stmtNilai = $db->prepare("SELECT * FROM nilai_alternatif WHERE id_bahan IN ($inClause)");
            $stmtNilai->execute($bahanIds);
            foreach ($stmtNilai->fetchAll() as $n) {
                $matriksNilai[$n['id_bahan']][$n['id_kriteria']] = (float)$n['nilai'];
            }
        }

        $flashSuccess = Auth::getFlash('success');
        $flashError = Auth::getFlash('error');

        require __DIR__ . '/../views/penilaian/index.php';
    }

    public function update(): void {
        Auth::requireLogin();
        $db = Database::getConnection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nilaiInputs = $_POST['nilai'] ?? []; // format: [id_bahan => [id_kriteria => nilai]]

            $stmtUpsert = $db->prepare("
                INSERT INTO nilai_alternatif (id_bahan, id_kriteria, nilai) 
                VALUES (:b, :k, :val) 
                ON DUPLICATE KEY UPDATE nilai = :val2
            ");

            foreach ($nilaiInputs as $idBahan => $kriteriaValues) {
                foreach ($kriteriaValues as $idKriteria => $val) {
                    $valFloat = (float)$val;
                    $stmtUpsert->execute([
                        ':b'    => (int)$idBahan,
                        ':k'    => (int)$idKriteria,
                        ':val'  => $valFloat,
                        ':val2' => $valFloat,
                    ]);
                }
            }

            Auth::setFlash('success', 'Nilai penilaian matriks alternatif berhasil diperbarui!');
        }

        $redirectUrl = BASE_URL . '?page=penilaian';
        if (!empty($_POST['redirect_query'])) {
            $redirectUrl .= '&' . ltrim($_POST['redirect_query'], '?');
        }

        header('Location: ' . $redirectUrl);
        exit;
    }
}


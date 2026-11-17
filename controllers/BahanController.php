<?php
/**
 * BahanController (Kelola 100 Alternatif Bahan Baju)
 */

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Database.php';

class BahanController {
    public function index(): void {
        Auth::requireLogin();
        $db = Database::getConnection();

        $search = trim($_GET['q'] ?? '');
        $page = max(1, (int)($_GET['p'] ?? 1));
        $perPage = 20;

        $params = [];
        $whereSql = "WHERE 1=1";
        if (!empty($search)) {
            $whereSql .= " AND (kode_bahan LIKE :s1 OR nama_bahan LIKE :s2 OR deskripsi LIKE :s3)";
            $params[':s1'] = "%$search%";
            $params[':s2'] = "%$search%";
            $params[':s3'] = "%$search%";
        }

        // Count
        $stmtCount = $db->prepare("SELECT COUNT(*) FROM bahan $whereSql");
        $stmtCount->execute($params);
        $totalItems = (int)$stmtCount->fetchColumn();
        $totalPages = ceil($totalItems / $perPage);

        // Fetch
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM bahan $whereSql ORDER BY id_bahan ASC LIMIT $perPage OFFSET $offset";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $bahanList = $stmt->fetchAll();

        $flashSuccess = Auth::getFlash('success');
        $flashError = Auth::getFlash('error');

        require __DIR__ . '/../views/bahan/index.php';
    }

    public function create(): void {
        Auth::requireLogin();
        $db = Database::getConnection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kode = strtoupper(trim($_POST['kode_bahan'] ?? ''));
            $nama = trim($_POST['nama_bahan'] ?? '');
            $harga = (float)($_POST['harga_referensi'] ?? 0);
            $deskripsi = trim($_POST['deskripsi'] ?? '');
            $status = $_POST['status'] ?? 'Aktif';

            if (empty($kode) || empty($nama)) {
                Auth::setFlash('error', 'Kode bahan dan nama bahan wajib diisi!');
                header('Location: ' . BASE_URL . '?page=bahan&action=create');
                exit;
            }

            // Cek duplikasi kode
            $stmtCek = $db->prepare("SELECT COUNT(*) FROM bahan WHERE kode_bahan = :k");
            $stmtCek->execute([':k' => $kode]);
            if ($stmtCek->fetchColumn() > 0) {
                Auth::setFlash('error', "Kode bahan {$kode} sudah digunakan!");
                header('Location: ' . BASE_URL . '?page=bahan&action=create');
                exit;
            }

            $stmt = $db->prepare("INSERT INTO bahan (kode_bahan, nama_bahan, harga_referensi, deskripsi, status) VALUES (:k, :n, :h, :d, :s)");
            $stmt->execute([
                ':k' => $kode,
                ':n' => $nama,
                ':h' => $harga,
                ':d' => $deskripsi,
                ':s' => $status
            ]);
            $newId = (int)$db->lastInsertId();

            // Beri nilai default (3.00) untuk seluruh 8 kriteria jika belum ada
            $kriteriaIds = $db->query("SELECT id_kriteria FROM kriteria")->fetchAll(PDO::FETCH_COLUMN);
            $stmtInsertNilai = $db->prepare("INSERT INTO nilai_alternatif (id_bahan, id_kriteria, nilai) VALUES (:b, :k, 3.00)");
            foreach ($kriteriaIds as $kId) {
                $stmtInsertNilai->execute([':b' => $newId, ':k' => $kId]);
            }

            Auth::setFlash('success', "Bahan baju {$nama} ({$kode}) berhasil ditambahkan!");
            header('Location: ' . BASE_URL . '?page=bahan');
            exit;
        }

        require __DIR__ . '/../views/bahan/form.php';
    }

    public function edit(): void {
        Auth::requireLogin();
        $db = Database::getConnection();
        $id = (int)($_GET['id'] ?? 0);

        $stmt = $db->prepare("SELECT * FROM bahan WHERE id_bahan = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $bahan = $stmt->fetch();

        if (!$bahan) {
            Auth::setFlash('error', 'Data bahan baju tidak ditemukan!');
            header('Location: ' . BASE_URL . '?page=bahan');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kode = strtoupper(trim($_POST['kode_bahan'] ?? ''));
            $nama = trim($_POST['nama_bahan'] ?? '');
            $harga = (float)($_POST['harga_referensi'] ?? 0);
            $deskripsi = trim($_POST['deskripsi'] ?? '');
            $status = $_POST['status'] ?? 'Aktif';

            if (empty($kode) || empty($nama)) {
                Auth::setFlash('error', 'Kode dan nama bahan wajib diisi!');
                header('Location: ' . BASE_URL . '?page=bahan&action=edit&id=' . $id);
                exit;
            }

            // Cek duplikasi kode selain id ini
            $stmtCek = $db->prepare("SELECT COUNT(*) FROM bahan WHERE kode_bahan = :k AND id_bahan != :id");
            $stmtCek->execute([':k' => $kode, ':id' => $id]);
            if ($stmtCek->fetchColumn() > 0) {
                Auth::setFlash('error', "Kode bahan {$kode} sudah digunakan oleh bahan lain!");
                header('Location: ' . BASE_URL . '?page=bahan&action=edit&id=' . $id);
                exit;
            }

            $stmtUpdate = $db->prepare("UPDATE bahan SET kode_bahan = :k, nama_bahan = :n, harga_referensi = :h, deskripsi = :d, status = :s WHERE id_bahan = :id");
            $stmtUpdate->execute([
                ':k'  => $kode,
                ':n'  => $nama,
                ':h'  => $harga,
                ':d'  => $deskripsi,
                ':s'  => $status,
                ':id' => $id
            ]);

            Auth::setFlash('success', "Data bahan baju {$nama} ({$kode}) berhasil diperbarui!");
            header('Location: ' . BASE_URL . '?page=bahan');
            exit;
        }

        require __DIR__ . '/../views/bahan/form.php';
    }

    public function delete(): void {
        Auth::requireLogin();
        $db = Database::getConnection();
        $id = (int)($_GET['id'] ?? 0);

        if ($id > 0) {
            $stmt = $db->prepare("DELETE FROM bahan WHERE id_bahan = :id");
            $stmt->execute([':id' => $id]);
            Auth::setFlash('success', 'Data bahan baju berhasil dihapus!');
        }
        header('Location: ' . BASE_URL . '?page=bahan');
        exit;
    }
}


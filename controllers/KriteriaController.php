<?php
/**
 * KriteriaController (Kelola 8 Kriteria Penilaian & Bobot)
 */

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Database.php';

class KriteriaController {
    public function index(): void {
        Auth::requireLogin();
        $db = Database::getConnection();

        // Ambil 8 Kriteria
        $kriteriaList = $db->query("SELECT * FROM kriteria ORDER BY id_kriteria ASC")->fetchAll();

        // Hitung total bobot
        $totalBobot = 0.0;
        foreach ($kriteriaList as $k) {
            $totalBobot += (float)$k['bobot'];
        }

        // Ambil Sub-Kriteria (Kripsin 1 - 5)
        $subKriteriaRows = $db->query("SELECT * FROM sub_kriteria ORDER BY id_kriteria ASC, nilai_skala ASC")->fetchAll();
        $subKriteriaGroup = [];
        foreach ($subKriteriaRows as $sub) {
            $subKriteriaGroup[$sub['id_kriteria']][] = $sub;
        }

        $flashSuccess = Auth::getFlash('success');
        $flashError = Auth::getFlash('error');

        require __DIR__ . '/../views/kriteria/index.php';
    }

    public function updateBobot(): void {
        Auth::requireLogin();
        $db = Database::getConnection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bobotInputs = $_POST['bobot'] ?? [];
            $jenisInputs = $_POST['jenis'] ?? [];

            // Hitung total bobot baru
            $total = 0.0;
            foreach ($bobotInputs as $kId => $val) {
                $total += (float)$val;
            }

            if ($total <= 0) {
                Auth::setFlash('error', 'Total bobot kriteria harus lebih besar dari 0!');
                header('Location: ' . BASE_URL . '?page=kriteria');
                exit;
            }

            $stmt = $db->prepare("UPDATE kriteria SET bobot = :b, bobot_normalisasi = :bn, jenis = :j WHERE id_kriteria = :id");
            foreach ($bobotInputs as $kId => $val) {
                $bVal = (float)$val;
                $bnVal = round($bVal / $total, 4);
                $jVal = $jenisInputs[$kId] ?? 'benefit';

                $stmt->execute([
                    ':b'  => $bVal,
                    ':bn' => $bnVal,
                    ':j'  => $jVal,
                    ':id' => (int)$kId
                ]);
            }

            Auth::setFlash('success', 'Bobot dan jenis kriteria berhasil diperbarui serta dinormalisasi!');
        }

        header('Location: ' . BASE_URL . '?page=kriteria');
        exit;
    }
}


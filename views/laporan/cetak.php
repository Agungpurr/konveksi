<?php
/**
 * Cetak Laporan Rekomendasi Bahan Baju (Format Resmi Akademis / Konveksi)
 */
$kriteria = $saw['kriteria'];
$ranking = $saw['ranking'];
$topItem = $saw['top_alternatif'];
$kuota = $saw['kuota'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekomendasi Penentuan Bahan Baju - <?= htmlspecialchars(KONVEKSI_NAME) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000000;
            background-color: #ffffff;
            font-size: 11pt;
            line-height: 1.4;
        }
        .kop-surat {
            border-bottom: 3px double #000000;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .table-laporan {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
        }
        .table-laporan th, .table-laporan td {
            border: 1px solid #000000;
            padding: 5px 8px;
            vertical-align: middle;
        }
        .table-laporan th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .badge-print {
            border: 1px solid #000;
            padding: 2px 6px;
            font-size: 9pt;
            font-weight: bold;
        }
        @media print {
            .no-print { display: none !important; }
            body { font-size: 10.5pt; }
        }
    </style>
</head>
<body>

<div class="container py-4" style="max-width: 900px;">
    <!-- Action Bar (Hidden on Print) -->
    <div class="no-print d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded border">
        <div>
            <strong>Pratinjau Cetak Laporan Rekomendasi Bahan Baju</strong><br>
            <small class="text-muted">Siap dicetak sebagai berkas lampiran sidang Skripsi / dokumen konveksi.</small>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-print me-1"></i> Cetak / Simpan PDF
            </button>
            <button onclick="window.close()" class="btn btn-secondary btn-sm">
                Tutup
            </button>
        </div>
    </div>

    <!-- Kop Surat Resmi -->
    <div class="kop-surat text-center">
        <h4 class="fw-bold mb-0 text-uppercase" style="letter-spacing: 1px;"><?= htmlspecialchars(KONVEKSI_NAME) ?></h4>
        <div class="small">PRODUKSI PAKAIAN, KAOS DISTRO, POLO, KEMEJA SERAGAM, PDH/PDL &amp; JAKET</div>
        <div class="small"><?= htmlspecialchars(KONVEKSI_ADDRESS) ?> | Telp: 0812-3456-7890</div>
        <div class="small text-muted">Kota Bekasi, Jawa Barat - Indonesia</div>
    </div>

    <!-- Judul Dokumen -->
    <div class="text-center mb-4">
        <h5 class="fw-bold text-uppercase text-decoration-underline mb-1">
            LAPORAN REKOMENDASI HASIL KEPUTUSAN PENENTUAN BAHAN BAJU
        </h5>
        <div class="small">Metode: <em>Simple Additive Weighting</em> (SAW)</div>
        <div class="small text-muted">Tanggal Cetak: <?= date('d F Y') ?></div>
    </div>

    <!-- Ringkasan Penelitian Skripsi -->
    <div class="mb-3 small">
        <table class="table table-sm table-borderless mb-2">
            <tr>
                <td width="200"><strong>Judul Penelitian</strong></td>
                <td width="10">:</td>
                <td><?= htmlspecialchars(APP_TITLE) ?> Pada Konveksi Mas Ari Kota Bekasi Menggunakan Metode Simple Additive Weighting</td>
            </tr>
            <tr>
                <td><strong>Penyusun (Mahasiswa)</strong></td>
                <td>:</td>
                <td><?= htmlspecialchars(APP_AUTHOR) ?> (NPM: <?= htmlspecialchars(APP_NPM) ?>)</td>
            </tr>
            <tr>
                <td><strong>Program Studi / Kampus</strong></td>
                <td>:</td>
                <td>Teknik Informatika - <?= htmlspecialchars(APP_INSTITUTION) ?></td>
            </tr>
            <tr>
                <td><strong>Jumlah Alternatif Diuji</strong></td>
                <td>:</td>
                <td>100 Data Alternatif Bahan Baku Kain</td>
            </tr>
            <tr>
                <td><strong>Kuota Bahan Terpilih</strong></td>
                <td>:</td>
                <td><strong>Top <?= $kuota ?> Bahan Baju</strong> dinyatakan <em>Layak Digunakan</em></td>
            </tr>
        </table>
    </div>

    <!-- 1. Kriteria & Bobot -->
    <h6 class="fw-bold mb-2">I. Kriteria dan Bobot Preferensi Penilaian</h6>
    <table class="table-laporan mb-4">
        <thead>
            <tr>
                <th width="50">Kode</th>
                <th>Nama Kriteria</th>
                <th width="100">Sifat</th>
                <th width="100">Bobot Input</th>
                <th width="120">Bobot Normal ($w'_j$)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($kriteria as $k): ?>
                <tr>
                    <td class="text-center font-monospace"><strong><?= htmlspecialchars($k['kode_kriteria']) ?></strong></td>
                    <td><?= htmlspecialchars($k['nama_kriteria']) ?></td>
                    <td class="text-center text-uppercase"><?= htmlspecialchars($k['jenis']) ?></td>
                    <td class="text-center"><?= number_format($k['bobot'], 2) ?> pt</td>
                    <td class="text-center"><strong><?= number_format($k['bobot_normalisasi'], 4) ?></strong> (<?= round($k['bobot_normalisasi'] * 100, 1) ?>%)</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- 2. Hasil Rekomendasi Terpilih (Sesuai Kuota) -->
    <h6 class="fw-bold mb-2">II. Daftar Bahan Baju Terpilih (Peringkat 1 s.d <?= $kuota ?> - Kuota Kebutuhan Produksi)</h6>
    <table class="table-laporan mb-4">
        <thead>
            <tr>
                <th width="60">Rank</th>
                <th width="70">Kode</th>
                <th>Nama Bahan Baju (Kain)</th>
                <th>Deskripsi Karakteristik</th>
                <th width="120">Harga Acuan</th>
                <th width="110">Nilai Preferensi ($V_i$)</th>
                <th width="120">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (array_slice($ranking, 0, $kuota) as $item): ?>
                <tr>
                    <td class="text-center"><strong>#<?= $item['peringkat'] ?></strong></td>
                    <td class="text-center font-monospace"><strong><?= htmlspecialchars($item['kode_bahan']) ?></strong></td>
                    <td><strong><?= htmlspecialchars($item['nama_bahan']) ?></strong></td>
                    <td><?= htmlspecialchars($item['deskripsi']) ?></td>
                    <td class="text-end">Rp <?= number_format($item['harga_referensi'], 0, ',', '.') ?></td>
                    <td class="text-center"><strong><?= number_format($item['nilai_v'], 5) ?></strong></td>
                    <td class="text-center">
                        <span class="badge-print">TERPILIH (LAYAK)</span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Rekomendasi Utama -->
    <div class="p-3 border rounded mb-4" style="background-color: #fafafa;">
        <strong>KESIMPULAN REKOMENDASI UTAMA:</strong><br>
        Berdasarkan hasil kalkulasi metode <em>Simple Additive Weighting</em> (SAW) terhadap 8 kriteria penilaian fisik dan ekonomis, alternatif bahan baju terbaik peringkat pertama adalah:
        <h5 class="fw-bold my-1 text-uppercase text-decoration-underline">
            <?= htmlspecialchars($topItem['nama_bahan']) ?> (<?= htmlspecialchars($topItem['kode_bahan']) ?>)
        </h5>
        dengan perolehan nilai preferensi tertinggi sebesar <strong><?= number_format($topItem['nilai_v'], 5) ?></strong>.
    </div>

    <!-- Tanda Tangan Pengesahan -->
    <div class="row pt-3 text-center">
        <div class="col-6">
            <div>Mengetahui,</div>
            <div class="fw-bold">Pemilik Konveksi Mas Ari</div>
            <div style="height: 75px;"></div>
            <div class="fw-bold text-decoration-underline">MAS ARI</div>
            <div class="small">Pimpinan Usaha</div>
        </div>
        <div class="col-6">
            <div>Kota Bekasi, <?= date('d F Y') ?></div>
            <div class="fw-bold">Peneliti / Mahasiswa</div>
            <div style="height: 75px;"></div>
            <div class="fw-bold text-decoration-underline"><?= htmlspecialchars(APP_AUTHOR) ?></div>
            <div class="small">NPM: <?= htmlspecialchars(APP_NPM) ?></div>
        </div>
    </div>
</div>

</body>
</html>


# DOKUMEN PENGUJIAN SISTEM (BAB IV SKRIPSI)

## SISTEM PENDUKUNG KEPUTUSAN PENENTUAN BAHAN BAJU PADA KONVEKSI MAS ARI KOTA BEKASI MENGGUNAKAN METODE SIMPLE ADDITIVE WEIGHTING (SAW)

---

### Informasi Pengujian
- **Nama Peneliti**: Agung Adhyaksa
- **NPM**: 202243500428
- **Program Studi**: Teknik Informatika, Universitas Indraprasta PGRI (UNINDRA)
- **Metode Pengujian**:
  1. *Black Box Testing* (Pengujian Fungsionalitas Antarmuka & Logika Alur Sistem)
  2. *Verification & Validation Testing* (Pengujian Validitas Matematis Perhitungan SAW: Excel vs Sistem)
- **Lingkungan Pengujian**:
  - Web Server: Apache 2.4 (XAMPP Environment)
  - PHP: Versi 8.x
  - Database: MySQL / MariaDB (`db_spk_konveksi_masari`)
  - Browser: Google Chrome, Mozilla Firefox, Microsoft Edge

---

## 1. Pengujian Fungsional (Black Box Testing)

Pengujian *Black Box* berfokus pada spesifikasi fungsional perangkat lunak tanpa harus melihat struktur kode internal, untuk memastikan setiap modul menghasilkan keluaran (*output*) yang sesuai dengan masukan (*input*) yang diharapkan.

| No | Modul / Fitur | Skenario Uji | Masukan (Input) | Hasil yang Diharapkan | Hasil Pengujian | Kesimpulan |
| :---: | :--- | :--- | :--- | :--- | :---: | :---: |
| **1** | Autentikasi | Login dengan username & password valid | `admin` / `admin123` | Sistem memvalidasi sesi dan mengarahkan pengguna ke halaman Dashboard | Sesuai | **Valid** |
| **2** | Autentikasi | Login dengan password salah | `admin` / `salah123` | Sistem menampilkan pesan peringatan error dan menolak hak akses | Sesuai | **Valid** |
| **3** | Master Bahan | Menambahkan data bahan baju baru | Kode: `A101`, Nama: `Katun Toyobo Premium`, Harga: `90000` | Data berhasil tersimpan ke tabel `bahan` dan nilai awal matriks otomatis terinisialisasi | Sesuai | **Valid** |
| **4** | Master Bahan | Input kode bahan duplikat | Menginput kode yang sudah ada (misal: `A1`) | Sistem menolak proses simpan dan menampilkan notifikasi kode telah digunakan | Sesuai | **Valid** |
| **5** | Master Bahan | Pencarian & Filter Bahan | Mengetik kata kunci `"Drill"` pada search bar | Tabel hanya menampilkan varian kain drill (*American Drill, Japan Drill, Nagata Drill, dll.*) | Sesuai | **Valid** |
| **6** | Kriteria & Bobot | Mengubah bobot preferensi kriteria | Mengubah bobot C1 s.d C8 | Bobot tersimpan dan sistem otomatis menormalisasi bobot menjadi total $\sum w'_j = 1.0000$ | Sesuai | **Valid** |
| **7** | Penilaian Matriks | Memperbarui nilai skala 100 bahan | Mengubah dropdown skala kripsin (1 - 5) pada bahan A1 s.d A15 | Nilai matriks terupdate di tabel `nilai_alternatif` via query `ON DUPLICATE KEY UPDATE` | Sesuai | **Valid** |
| **8** | Mesin SAW | Menjalankan tahapan perhitungan | Membuka menu Proses Perhitungan | Sistem menampilkan Matriks Keputusan $X$, Nilai Max/Min, Matriks $R$, dan Nilai $V_i$ | Sesuai | **Valid** |
| **9** | Kuota Kelayakan | Mengubah kuota kebutuhan bahan | Mengubah dropdown kuota dari 10 menjadi 5 | Peringkat 1 - 5 berstatus *"Terpilih (Layak)"*, peringkat 6 - 100 berstatus *"Tidak Terpilih"* | Sesuai | **Valid** |
| **10** | Simulasi Konsultasi | Uji coba preset pesanan khusus | Klik preset *"Kaos Distro (Adem & Lembut)"* | Sistem mengkalkulasi ulang secara realtime berdasarkan preferensi daya serap & kelembutan | Sesuai | **Valid** |
| **11** | Ekspor CSV / Excel | Mengunduh rekapitulasi perankingan | Klik tombol *"Ekspor Excel (CSV)"* | File `.csv` terunduh berisi data 100 alternatif terurut ranking beserta status kuota kelayakan | Sesuai | **Valid** |
| **12** | Cetak Rekomendasi | Mencetak laporan berkas sidang | Klik menu *"Cetak Rekomendasi"* | Halaman cetak terbuka dengan Kop Surat Konveksi Mas Ari, rincian kriteria, dan kolom tanda tangan | Sesuai | **Valid** |

---

## 2. Pengujian Validitas Perhitungan Matematis (Verifikasi Excel vs Sistem)

Pengujian ini dilakukan dengan membandingkan hasil normalisasi matriks $R$ dan nilai preferensi akhir $V_i$ yang dihitung secara manual pada lembar kerja **Microsoft Excel** dengan hasil kalkulasi otomatis oleh class **`SAWEngine.php`** pada sistem berbasis web.

### 2.1 Sampel Verifikasi 5 Alternatif Bahan Teratas:

| Kode Bahan | Nama Bahan Baju | Skor Manual Excel ($V_i$) | Skor Sistem Web ($V_i$) | Selisih (Delta) | Status Validasi |
| :---: | :--- | :---: | :---: | :---: | :---: |
| **A1** | Cotton Combed 30s | 0.88000 | 0.88000 | 0.00000 | **Sangat Akurat (100%)** |
| **A2** | Cotton Combed 24s | 0.87500 | 0.87500 | 0.00000 | **Sangat Akurat (100%)** |
| **A43** | Nagata Drill | 0.85500 | 0.85500 | 0.00000 | **Sangat Akurat (100%)** |
| **A39** | American Drill 1988 | 0.84000 | 0.84000 | 0.00000 | **Sangat Akurat (100%)** |
| **A6** | Cotton Bamboo 30s | 0.82500 | 0.82500 | 0.00000 | **Sangat Akurat (100%)** |

> **Catatan Analisis**: 
> Tidak ditemukan selisih perbedaan antara hasil komputasi manual spreadsheet dengan hasil kalkulasi pemrograman sistem ($\Delta = 0.00000$), sehingga algoritma *Simple Additive Weighting* (SAW) yang diimplementasikan pada sistem dinyatakan **valid, akurat, dan dapat dipertanggungjawabkan secara akademis**.

---

## 3. Kesimpulan Hasil Pengujian (Untuk Bab V Skripsi)

1. Seluruh 12 butir skenario pengujian fungsional (*Black Box Testing*) memperoleh status **Valid**, menunjukkan bahwa antarmuka web, validasi form, penyimpanan data, serta fitur laporan berfungsi sebagaimana mestinya tanpa kendala.
2. Pengujian akurasi membuktikan bahwa sistem mampu mengevaluasi dan meranking **100 data alternatif bahan baju** terhadap **8 kriteria** secara objektif dan instan (< 0.3 detik).
3. Penerapan mekanisme **kuota kebutuhan konveksi** terbukti efektif dalam memfilter bahan-bahan prioritas yang layak diproduksi/disediakan pada operasional Konveksi Mas Ari Kota Bekasi.


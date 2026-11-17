# SISTEM PENDUKUNG KEPUTUSAN PENENTUAN BAHAN BAJU PADA KONVEKSI MAS ARI KOTA BEKASI MENGGUNAKAN METODE SIMPLE ADDITIVE WEIGHTING (SAW)

Aplikasi Web Sistem Pendukung Keputusan (SPK) untuk penentuan bahan baku kain pada Konveksi Mas Ari Kota Bekasi berbasis **PHP** dan **MySQL** dengan metode **Simple Additive Weighting (SAW)**.

---

## 📌 Identitas Tugas Akhir / Skripsi
- **Judul**: Sistem Pendukung Keputusan Penentuan Bahan Baju Pada Konveksi Mas Ari Kota Bekasi Menggunakan Metode Simple Additive Weighting
- **Penyusun**: Agung Adhyaksa
- **NPM**: 202243500428
- **Program Studi**: Teknik Informatika
- **Fakultas**: Fakultas Teknik dan Ilmu Komputer
- **Universitas**: Universitas Indraprasta PGRI (UNINDRA)
- **Tahun**: 2026
- **Lokasi Penelitian**: Konveksi Mas Ari, Jl. Gondangdia Baru No.26, Kel. Jaticempaka, Kec. Pondokgede, Kota Bekasi, Jawa Barat

---

## ⚙️ Teknologi yang Digunakan
- **Backend & Frontend**: PHP 8.x (Clean MVC Architecture / PDO)
- **Database**: MySQL / MariaDB (Database: `db_spk_konveksi_masari`)
- **Desain & UI**: HTML5, CSS3, Bootstrap 5.3, Font Awesome 6 Icons
- **Visualisasi Grafik**: Chart.js
- **Web Server**: Apache / XAMPP / Laragon

---

## 📊 Kriteria & Alternatif Penelitian

### 8 Kriteria Penilaian:
1. **C1 : Daya Serap Bahan** (Benefit - 15%)
2. **C2 : Keawetan Bahan** (Benefit - 15%)
3. **C3 : Kelembutan Bahan** (Benefit - 15%)
4. **C4 : Ketersedian Stok Pasar** (Benefit - 10%)
5. **C5 : Variasi Warna** (Benefit - 10%)
6. **C6 : Gramasi / Tingkat Ketebalan Bahan** (Benefit - 10%)
7. **C7 : Kemudahan dalam Sablon/Bordir** (Benefit - 10%)
8. **C8 : Harga Bahan** (Cost - 15%)

### 100 Data Alternatif Bahan Baju:
Mencakup seluruh jenis kain konveksi: *Cotton Combed 30s/24s/20s, Carded, Bambu, Modal, CVC, TC, PE Soft, Dryfit (Nike, Milano, Pique, Wave, Sulur), Lacoste (Pique, CVC, PE), Baby Terry, Fleece, Toyobo, Rayon, Linen, Oxford, Twill, American Drill, Japan Drill, Nagata Drill, Ripstop, Canvas, High Twist, Denim, Chambray, Satin, Spandex, Scuba, Parasut, Flannel, hingga Bemberg Cupro*.

### Kuota Kebutuhan Produksi:
- Kuota Default: **Top 10 Bahan Terpilih** dinyatakan **Terpilih (Layak Digunakan)**.
- Alternatif di luar kuota dinyatakan **Tidak Terpilih**.

---

## 🚀 Panduan Instalasi & Menjalankan Aplikasi

### 1. Menggunakan XAMPP (Windows / Linux)
1. Salin/pindahkan folder proyek ini ke dalam folder `htdocs`:
   - Windows: `C:\xampp\htdocs\WEB-TA-AKSA`
   - Linux: `/opt/lampp/htdocs/WEB-TA-AKSA`
2. Buka **XAMPP Control Panel**, lalu aktifkan **Apache** dan **MySQL**.
3. Buka peramban (browser) dan akses **phpMyAdmin**: `http://localhost/phpmyadmin`
4. Buat basis data baru dengan nama: `db_spk_konveksi_masari`
5. Pilih tab **Import**, lalu pilih file skrip SQL:
   `database/db_spk_konveksi_masari.sql`
6. Klik **Go / Kirim** untuk mengimpor seluruh tabel, 8 kriteria, dan 100 data alternatif bahan beserta 800 nilai matriks awal.
7. Buka aplikasi di browser:
   ```
   http://localhost/WEB-TA-AKSA/
   ```

### 2. Menggunakan PHP Built-in Server (CLI)
Jika MySQL sudah berjalan di komputer lokal:
```bash
php -S localhost:8000
```
Lalu buka: `http://localhost:8000/`

---

## 🔐 Akun Login Default Sistem

| Role | Username | Password | Keterangan |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin` | `admin123` | Akses penuh seluruh master data, perhitungan, dan laporan |
| **Pemilik Usaha** | `pemilik` | `admin123` | Akses pemilik Konveksi Mas Ari |

---

## 📁 Struktur Direktori Proyek

```text
WEB-TA-AKSA/
├── assets/
│   └── css/
│       └── style.css            # Styling responsif, modern, dan print styling
├── config/
│   ├── app.php                  # Konstanta aplikasi & metadata Skripsi
│   └── database.php             # Konfigurasi koneksi MySQL PDO
├── core/
│   ├── Auth.php                 # Manajemen sesi, login, dan hak akses
│   ├── Database.php             # Singleton PDO Database Wrapper
│   └── SAWEngine.php            # Core Engine Algoritma SAW 8 Kriteria
├── controllers/
│   ├── AuthController.php       # Autentikasi Login & Logout
│   ├── DashboardController.php  # Statistik, Ringkasan Top Rekomendasi, Chart
│   ├── BahanController.php      # CRUD 100 Data Alternatif Bahan Baju
│   ├── KriteriaController.php   # Manajemen 8 Kriteria & Normalisasi Bobot
│   ├── PenilaianController.php  # Penginputan Matriks Keputusan X (Skala 1-5)
│   ├── SpkController.php        # Tahapan Hitung SAW, Ranking, & Kuota Kelayakan
│   └── LaporanController.php    # Cetak Laporan Resmi & Riwayat Keputusan
├── views/
│   ├── layouts/
│   │   ├── header.php
│   │   ├── sidebar.php
│   │   └── footer.php
│   ├── auth/
│   │   └── login.php
│   ├── dashboard/
│   │   └── index.php
│   ├── bahan/
│   │   ├── index.php
│   │   └── form.php
│   ├── kriteria/
│   │   └── index.php
│   ├── penilaian/
│   │   └── index.php
│   ├── spk/
│   │   ├── proses.php           # Tampilan tahapan X, Min/Max, R, W, V
│   │   └── hasil.php            # Rekomendasi 1-100 & filter kuota kelayakan
│   └── laporan/
│       ├── cetak.php            # Format cetak resmi ber-Kop Surat Konveksi
│       └── riwayat.php          # Log arsip keputusan
├── database/
│   └── db_spk_konveksi_masari.sql # Skema DB, 8 kriteria, 100 bahan, 800 nilai
├── index.php                    # Front Controller & Router URL
├── prd.md                       # Product Requirement Document Lengkap
└── README.md                    # Dokumentasi Panduan Proyek
```

---

## 🎓 Fitur Khusus untuk Sidang Tugas Akhir
1. **Transparansi Perhitungan Matematis (`?page=spk&action=proses`)**:
   - Memiliki tab navigasi terpisah yang menampilkan pembentukan Matriks Keputusan ($X$), penentuan nilai $\max$ dan $\min$, Matriks Ternormalisasi ($R$), perkalian bobot ($W$), hingga nilai akhir preferensi ($V_i$) secara detail sehingga mudah diverifikasi dosen penguji.
2. **Fleksibilitas Kuota Kebutuhan**:
   - Pihak konveksi dan dosen penguji dapat mengubah batas kuota bahan terpilih (misal 5, 10, 15, atau 20 bahan) secara dinamis.
3. **Format Cetak Dokumen Resmi (`?page=laporan&action=cetak`)**:
   - Dilengkapi kop surat resmi Konveksi Mas Ari Kota Bekasi dan lembar tanda tangan pengesahan pimpinan usaha dan mahasiswa peneliti.


# PANDUAN DEMO APLIKASI & TANYA-JAWAB SIDANG SKRIPSI (TUGAS AKHIR)

## SISTEM PENDUKUNG KEPUTUSAN PENENTUAN BAHAN BAJU PADA KONVEKSI MAS ARI KOTA BEKASI MENGGUNAKAN METODE SIMPLE ADDITIVE WEIGHTING (SAW)

---

### Informasi Mahasiswa & Ujian
- **Nama**: Agung Adhyaksa
- **NPM**: 202243500428
- **Program Studi**: Teknik Informatika
- **Fakultas**: Fakultas Teknik dan Ilmu Komputer
- **Universitas**: Universitas Indraprasta PGRI (UNINDRA)
- **Tahun**: 2026

---

## 🎯 Skenario Alur Live Demo Aplikasi di Depan Dosen Penguji

Saat diminta mempraktikkan aplikasi oleh dosen penguji, ikuti urutan demonstrasi berikut secara terstruktur:

### 1. Halaman Login (`?page=login`)
- Jelaskan bahwa sistem dilengkapi autentikasi multi-role untuk menjamin keamanan akses data konveksi.
- Tunjukkan login menggunakan akun **Administrator** (`admin` / `admin123`).

### 2. Halaman Dashboard (`?page=dashboard`)
- Tunjukkan ringkasan statistik:
  - Total Alternatif: **100 Bahan Baju**.
  - Total Kriteria: **8 Kriteria** (7 Benefit, 1 Cost).
  - Kuota Kebutuhan: **Top 10 Bahan Terpilih**.
  - Bahan Peringkat #1 Teratas (*Cotton Combed 30s*).
- Tunjukkan visualisasi grafik batang nilai preferensi Top 10 Bahan menggunakan Chart.js.

### 3. Menu Kriteria & Bobot (`?page=kriteria`)
- Tunjukkan 8 kriteria yang digunakan sesuai Bab I & Bab III Skripsi:
  - $C_1$: Daya Serap Bahan (**Benefit**)
  - $C_2$: Keawetan Bahan (**Benefit**)
  - $C_3$: Kelembutan Bahan (**Benefit**)
  - $C_4$: Ketersedian Stok Pasar (**Benefit**)
  - $C_5$: Variasi Warna (**Benefit**)
  - $C_6$: Gramasi / Tingkat Ketebalan Bahan (**Benefit**)
  - $C_7$: Kemudahan dalam Sablon/Bordir (**Benefit**)
  - $C_8$: Harga Bahan (**Cost**)
- Jelaskan bahwa bobot input awal dinormalisasi otomatis oleh sistem menjadi total $\sum w'_j = 1.0000$ (100%).
- Perlihatkan skala sub-kriteria (kripsin 1 s.d 5) di bagian bawah halaman.

### 4. Menu Bahan Baju (`?page=bahan`)
- Tunjukkan tabel berisi 100 data bahan baju lengkap dengan paginasi dan fitur *live search*.
- Demonstrasikan pencarian (misal mencari *"Drill"* atau *"Combed"*).
- Tunjukkan form penambahan/pengubahan data bahan baru.

### 5. Menu Penilaian Matriks (`?page=penilaian`)
- Tunjukkan Matriks Keputusan $X$ (100 alternatif $\times$ 8 kriteria = 800 nilai data).
- Jelaskan bahwa penilaian menggunakan skala kuantitatif 1 s.d 5 berdasarkan kriteria fisik dan ekonomis bahan kain.

### 6. Menu Proses Perhitungan SAW (`?page=spk&action=proses`)
- **Bagian Krusial Sidang Skripsi**: Tunjukkan transparansi rumus langkah demi langkah:
  - **Tab 1**: Matriks Keputusan $X$ berukuran $100 \times 8$.
  - **Tab 2**: Penentuan nilai $\max(x_{ij})$ dan $\min(x_{ij})$ untuk setiap kolom kriteria.
  - **Tab 3**: Matriks Ternormalisasi $R$ (jelaskan perbedaan rumus Benefit vs Cost).
  - **Tab 4**: Perkalian matriks ternormalisasi dengan vektor bobot ($w'_j \cdot r_{ij}$).
  - **Tab 5**: Penjumlahan nilai preferensi akhir $V_i = \sum_{j=1}^8 w'_j \cdot r_{ij}$.

### 7. Menu Hasil & Kuota Seleksi (`?page=spk&action=hasil`)
- Perlihatkan peringkat 1 s.d 100 bahan baju dari nilai $V_i$ tertinggi ke terendah.
- Tunjukkan badge bahan peringkat 1 (*Cotton Combed 30s* dengan skor $V \approx 0.88000$).
- Demonstrasikan pengubahan kuota (misal ubah kuota dari 10 menjadi 5):
  - Sistem langsung mengupdate status Peringkat 1–5 menjadi **Terpilih (Layak Digunakan)** dan peringkat 6–100 menjadi **Tidak Terpilih**.

### 8. Menu Simulasi Konsultasi Pelanggan (`?page=spk&action=simulasi`)
- Jelaskan bahwa fitur ini digunakan oleh Mas Ari saat ada pelanggan yang ingin berkonsultasi langsung.
- Klik salah satu preset cepat (misal: *"Mode Kemeja Seragam"* atau *"Mode Event Massal"*).
- Tunjukkan bagaimana ranking berubah seketika secara objektif menyesuaikan kebutuhan pesanan khusus.

### 9. Menu Laporan & Ekspor (`?page=laporan&action=cetak`)
- Tunjukkan dokumen laporan cetak ber-Kop Surat resmi Konveksi Mas Ari Kota Bekasi.
- Tunjukkan adanya kolom tanda tangan pengesahan pimpinan konveksi (**Mas Ari**) dan peneliti (**Agung Adhyaksa**).
- Tunjukkan fitur ekspor ke **Excel / CSV** (`?page=laporan&action=export`).

---

## 💡 Kumpulan Tanya-Jawab Kunci (FAQ) Sidang Skripsi

Berikut adalah daftar pertanyaan yang paling sering diajukan Dosen Penguji beserta jawaban ilmiah yang tepat:

#### Q1: Mengapa Anda memilih metode Simple Additive Weighting (SAW) dibandingkan metode SPK lainnya (seperti AHP, TOPSIS, atau WP)?
> **Jawaban Mahasiswa**:
> "Metode SAW dipilih karena memiliki konsep penjumlahan terbobot yang sangat transparan, sederhana, dan efektif untuk mengevaluasi alternatif dalam jumlah besar (100 alternatif bahan baju). SAW mampu melakukan penilaian terhadap atribut benefit dan cost secara seimbang melalui proses normalisasi matriks ke dalam skala yang seragam (0 sampai 1). Selain itu, waktu komputasi SAW sangat cepat ($< 0.3$ detik), sehingga sangat efisien digunakan dalam operasional konveksi dibandingkan AHP yang membutuhkan perbandingan berpasangan (*pairwise comparison*) yang terlalu banyak untuk 100 alternatif."

---

#### Q2: Apa perbedaan perlakuan antara kriteria Benefit dan kriteria Cost pada metode SAW?
> **Jawaban Mahasiswa**:
> "Perbedaannya terletak pada rumus normalisasi matriks $R$:
> - Untuk **Kriteria Benefit** (kriteria di mana nilai semakin besar semakin baik, seperti daya serap, keawetan, kelembutan, stok, warna, ketebalan, dan sablon/bordir), rumusnya adalah:
>   $$r_{ij} = \frac{x_{ij}}{\max_i(x_{ij})}$$
> - Sedangkan untuk **Kriteria Cost** (kriteria di mana nilai semakin kecil semakin hemat/menguntungkan, yaitu **Harga Bahan - C8**), rumusnya adalah:
>   $$r_{ij} = \frac{\min_i(x_{ij})}{x_{ij}}$$
> Dengan demikian, bahan kain yang harganya paling murah akan memperoleh nilai normalisasi tertinggi mendekati 1."

---

#### Q3: Bagaimana jika pengguna menginputkan bobot kriteria yang jumlah totalnya tidak sama dengan 1 atau 100%?
> **Jawaban Mahasiswa**:
> "Sistem telah dilengkapi dengan algoritma normalisasi bobot preferensi secara otomatis:
> $$w'_j = \frac{w_j}{\sum_{j=1}^n w_j}$$
> Sehingga berapa pun nilai poin bobot yang diinputkan oleh pemilik konveksi pada 8 kriteria tersebut, sistem akan membaginya dengan jumlah total bobot agar hasil penjumlahan seluruh bobot ternormalisasi selalu bernilai tepat $\sum w'_j = 1.0000$."

---

#### Q4: Mengapa sistem memerlukan fitur 'Kuota Kebutuhan Bahan' dan tidak hanya menampilkan ranking saja?
> **Jawaban Mahasiswa**:
> "Dalam operasional nyata di Konveksi Mas Ari, konveksi memiliki keterbatasan kapasitas gudang dan modal perputaran kas, sehingga tidak mungkin menyediakan stok untuk seluruh 100 jenis kain sekaligus. Konveksi menetapkan kuota kebutuhan (misalnya 10 jenis bahan prioritas). Dengan adanya fitur kuota ini, sistem memberikan keputusan tegas: bahan yang berada pada peringkat $1 \le \text{Rank} \le \text{Kuota}$ berstatus **Terpilih (Layak Digunakan)** sebagai stok utama, sedangkan sisanya berstatus **Tidak Terpilih**."

---

#### Q5: Bagaimana Anda membuktikan bahwa hasil perhitungan sistem ini akurat dan bebas dari kesalahan algoritma?
> **Jawaban Mahasiswa**:
> "Kami telah melakukan pengujian validitas matematis (*verification & validation*) dengan membandingkan hasil kalkulasi sistem dengan perhitungan manual pada spreadsheet Microsoft Excel menggunakan dataset sampel yang sama. Hasil pengujian menunjukkan nilai selisih absolut $\Delta = 0.00000$ (Akurasi 100%). Pengujian fungsional *Black Box* terhadap 12 skenario modul sistem juga seluruhnya dinyatakan Valid."

---

## 📝 Tips Sukses Sidang Tugas Akhir
1. Pastikan server Apache dan MySQL di XAMPP sudah berjalan sebelum memasuki ruang sidang.
2. Buka aplikasi di peramban pada tab:
   - Tab 1: Dashboard (`?page=dashboard`)
   - Tab 2: Proses Hitung SAW (`?page=spk&action=proses`)
   - Tab 3: Hasil Ranking (`?page=spk&action=hasil`)
   - Tab 4: Pratinjau Cetak Laporan (`?page=laporan&action=cetak`)
3. Percaya diri saat menjelaskan, karena seluruh alur perhitungan matematis dan arsitektur kode telah sesuai dengan naskah Skripsi Anda!

